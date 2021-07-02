<?php

namespace App\Command\Generate;

use App\ImageSource\TwitterInteractionsImageSource;
use App\Service\TwitterServiceProvider;
use App\Storage;
use App\Template;
use GDaisy\Placeholder\ImagePlaceholder;
use GDaisy\PlaceholderInterface;
use Minicli\Command\CommandController;

class InteractionsController extends CommandController
{
    public function handle(): int
    {
        //Start by building a dynamic template
        $template = new Template('twitter-interactions', [
            'width' => 675,
            'height' => 1200,
        ]);

        //Set up BG as first element
        $template->addPlaceholder('background', new ImagePlaceholder([
            'width' => $template->width,
            'height' => $template->height,
            'pos_x' => 0,
            'pos_y' => 0,
            'image' => "app/Resources/images/interactions.png"
        ]));

        $limit = 30;
        $per_line = 5;
        $prefix = "int";
        $avatar_size = 100;
        $spacing = 25;
        $line = 1;
        $col = 1;

        /** @var TwitterServiceProvider $twitter */
        $twitter = $this->getApp()->twitter;

        //get own user credentials
        $owner = $twitter->client->get('/account/verify_credentials');

        //place owner avatar at the center
        $avatar_path = str_replace('normal', 'bigger', $owner->profile_image_url_https);
        $avatar = Storage::downloadImage($avatar_path);
        $template->addPlaceholder('owner', new ImagePlaceholder([
            'width' => $avatar_size*1.5,
            'height' => $avatar_size*1.5,
            'pos_x' => ($template->width/2) - ($avatar_size*1.5/2),
            'pos_y' => ($template->height/2) - ($avatar_size*1.5/2),
            'image' => $avatar,
            'filters' => [ "GDaisy\\Filter\\Circle" ]
        ]));

        $source = new TwitterInteractionsImageSource();
        $featured = $source->getImageList($this->getApp(), $limit, ['mutuals' => $this->hasFlag('mutuals')]);

        //build the template
        $linegap = ceil((count($featured) / 2) / $per_line);
        $start_x = 20;
        $start_y = 20;

        for ($i = 1; $i <= $limit; $i++) {
            $pos_x = $start_x + ($spacing*$col) + ($avatar_size*$col - $avatar_size);
            $pos_y = $start_y + ($spacing*$line) + ($avatar_size*$line - $avatar_size);
            $template->addPlaceholder($prefix . $i, new ImagePlaceholder([
                'width' => $avatar_size,
                'height' =>$avatar_size,
                'pos_x' => $pos_x,
                'pos_y' => $pos_y,
                'filters' => [ "GDaisy\\Filter\\Circle" ]
            ]));

            $col++;
            if ($i % $per_line == 0) {
                if ($line == $linegap) {
                    //second block starts at line 7
                    $line = 6;
                }
                $line++;
                $col = 1;
            }
        }

        //Apply template elements
        /**
         * @var string $key
         * @var PlaceholderInterface $placeholder
         */
        foreach ($template->placeholders as $key => $placeholder) {
            if ($placeholder instanceof ImagePlaceholder and $placeholder->image) {
                $placeholder->apply($template->getResource(), ['image_file' => $placeholder->image]);
                continue;
            }

            if (isset($featured[$key])) {
                $placeholder->apply($template->getResource(), $featured[$key]);
            }
        }

        $save_path = Storage::root() . 'latest_header.png';
        $template->write($save_path);
        $this->getPrinter()->info("Finished generating cover at $save_path.");

        return 0;
    }
}
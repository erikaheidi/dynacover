<?php

namespace App\Service;

use App\ApiModel;
use Minicli\App;
use Minicli\Curly\Client;
use Minicli\ServiceInterface;

class GithubServiceProvider implements ServiceInterface
{
    /** @var Client */
    public Client $agent;

    /** @var string Access Token */
    protected string $token;

    /**@var App */
    private App $app;

    static string $API_ENDPOINT = "https://api.github.com/graphql";

    public function load(App $app)
    {
        $this->agent = new Client();
        $this->token = $app->config->github_api_bearer;
        $this->app = $app;
    }

    public function graphqlQuery($query, array $params = []): array
    {
        $headers = $this->getAuthHeaders($this->token);

        return $this->agent->post(self::$API_ENDPOINT, ['query' => $query, 'variables' => $params], $headers);
    }

    public function getAuthHeaders($access_token): array
    {
        return [
            "User-Agent: Dynacover v0.2",
            "Content-Type: application/json",
            "Authorization: bearer $access_token"
        ];
    }

    public function getSponsorsList(): array
    {
        $query = <<<'JSON'
query{
  viewer {
    sponsorshipsAsMaintainer(first: 100) {
      pageInfo {
        hasNextPage,
        hasPreviousPage,
      }
      nodes {
        createdAt
        tier {
          name,
          monthlyPriceInDollars,
          isOneTime
        },
      	sponsor {
        	name,
          twitterUsername,
          login,
          avatarUrl,
          email,
        },
        id
      }
    }
  }
}
JSON;
        $response = $this->graphqlQuery($query);

        $sponsors_list = [];

        if ($response['code'] == 200) {
            $data = json_decode($response['body'], true);
            if($data['errors']) {
                $this->app->getPrinter()->error($data['errors'][0]['type'] . ' : ' . $data['errors'][0]['message']);
            }
            $pagination_info = $data['data']['viewer']['sponsorshipsAsMaintainer']['pageInfo'];
            $sponsors = $data['data']['viewer']['sponsorshipsAsMaintainer']['nodes'];

            foreach ($sponsors as $sponsor_data) {
                if ($sponsor_data['sponsor'] == null) {
                    continue;
                }

                $sponsor = new ApiModel();
                $sponsor->sponsorshipId = $sponsor_data['id'];
                $sponsor->createdAt = new \DateTimeImmutable($sponsor_data['createdAt']);
                $sponsor->tierName = $sponsor_data['tier']['name'];
                $sponsor->tierDollars = $sponsor_data['tier']['monthlyPriceInDollars'];
                $sponsor->isOneTime = $sponsor_data['tier']['isOneTime'];

                $sponsor->name = $sponsor_data['sponsor']['name'] ?? $sponsor_data['sponsor']['login'];
                $sponsor->login = $sponsor_data['sponsor']['login'];
                $sponsor->twitterUsername = $sponsor_data['sponsor']['twitterUsername'] ?? null;
                $sponsor->email = $sponsor_data['sponsor']['email'];
                $sponsor->avatarUrl = $sponsor_data['sponsor']['avatarUrl'];
                $sponsor->isActive = 1;

                $sponsors_list[] = $sponsor;
            }
        }

        return $sponsors_list;
    }
}
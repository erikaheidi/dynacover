FROM erikaheidi/minicli:php81

ARG user=minicli

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

USER $user

COPY . /home/$user

# Set working directory
WORKDIR /home/$user

# Install dependencies
RUN composer install

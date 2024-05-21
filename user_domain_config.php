<?php

declare(strict_types=1);

/*
 * This file is part of the Per-User-Domain Configuration Plugin for Roundcube.
 *
 * (ɔ) Frugan <dev@frugan.it>
 *
 * This source file is subject to the GNU GPLv3 license that is bundled
 * with this source code in the file COPYING.
 */

class user_domain_config extends rcube_plugin
{
    private $loaded = false;
    private $rcmail;

    public function init(): void
    {
        $this->rcmail = rcmail::get_instance();

        $this->add_hook('config_get', [$this, 'merge_config']);
    }

    public function merge_config($args): void
    {
        if (!$this->loaded) {
            if ('login' === $this->rcmail->task && 'login' === $this->rcmail->action) {
                $username = trim(rcube_utils::get_input_string('_user', rcube_utils::INPUT_POST));
            } else {
                $username = $this->rcmail->user->data['username'] ?? null;
            }

            if (!empty($username)) {
                $domain = substr($username, strpos($username, '@') + 1);

                if ($this->rcmail->config->load_from_file($domain.'.inc.php')) {
                    $this->loaded = true;
                }
            }
        }
    }
}

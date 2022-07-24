<?php

declare(strict_types=1);

namespace Shift\Install\Controller\Startup;

use Shift\System\Mvc;

class Language extends Mvc\Controller
{
    public function index()
    {
        // Default language code
        $code = $this->config->get('language_default');

        $languages = glob(DIR_LANGUAGE . '*', GLOB_ONLYDIR);

        foreach ($languages as $language) {
            $languages[] = basename($language);
        }

        if ($this->request->has('server.HTTP_ACCEPT_LANGUAGE')) {
            $browser_languages = explode(',', $this->request->get('server.HTTP_ACCEPT_LANGUAGE', ''));

            foreach ($browser_languages as $browser_language) {
                if (in_array($browser_language, $languages)) {
                    $code = $browser_language;
                    break;
                }
            }
        }

        if ($this->session->isEmpty('language') || !is_dir(DIR_LANGUAGE . basename($this->session->get('language')))) {
            $this->session->set('language', $code);
        }

        // Language
        $language = new Language($this->session->get('language'));
        $language->load($this->session->get('language'));
        $this->registry->set('language', $language);
    }
}

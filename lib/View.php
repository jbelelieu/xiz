<?php namespace Lib;

/**
 * Class View
 *
 * Renders a view.
 */
class View {

    protected $raw = '';

    protected $rendered = '';

    protected $changes = '';

    protected $data;

    protected $view = '';

    protected $viewPath = '';

    protected $filePath = '';

    protected $viewType = 'html';

    protected $theme = 'acw';

    protected $lang = 'en';

    protected $header = 'header';

    protected $footer = 'footer';


    /**
     * @return string
     */
    public function __toString()
    {
        return $this->rendered;
    }


    /**
     * @param $changes
     * @param string $value
     *
     * @return $this
     */
    public function macro($changes, $value = '')
    {
        if (is_array($changes) || is_object($changes)) {
            foreach ($changes as $key => $val) {
                $this->changes[$key] = $val;
            }
        } else {
            $this->changes[$changes] = $value;
        }

        return $this;
    }



    /**
     * @param $key
     * @param $data
     *
     * @return $this
     */
    public function data($key, $data)
    {
        $this->data[$key] = $data;

        return $this;
    }


    /**
     * @return $this
     */
    public function skipHeaderFooter()
    {
        $this->header = '';
        $this->footer = '';

        return $this;
    }


    /**
     * @param $header
     *
     * @return $this
     */
    public function setHeader($header)
    {
        $this->header = $header;

        return $this;
    }


    /**
     * @param $footer
     *
     * @return $this
     */
    public function setFooter($footer)
    {
        $this->footer = $footer;

        return $this;
    }


    /**
     * @param $theme
     *
     * @return $this
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;

        return $this;
    }


    /**
     * @param $lang
     *
     * @return $this
     */
    public function setLanguage($lang)
    {
        $this->lang = $lang;

        return $this;
    }


    /**
     * @param $type
     */
    public function setViewType($type)
    {
        switch (strtolower($type)) {
            case 'email':
                $this->viewType = 'email';
                break;
            default:
                $this->viewType = 'html';
        }
    }


    /**
     * @param $view
     *
     * @return $this
     */
    public function setView($view)
    {
        $this->filePath = dirname(dirname(__FILE__)) . '/views/' . $this->viewType . '/' . $this->theme . '/' . $this->lang;

        $file = $this->filePath . '/' . trim($view, '/') . '.php';

        if (file_exists($file)) {
            $this->view = $view;
            $this->viewPath = $file;
        } else {
            $this->view = dirname(dirname(__FILE__)) . '/views/awc/en/error.php';
        }

        return $this;
    }


    /**
     * @return mixed|string
     */
    public function render()
    {
        if (! empty($this->header)) {
            ob_start();
            include $this->filePath . '/' . $this->header . '.php';
            $this->raw .= ob_get_contents();
            ob_end_clean();
        }

        ob_start();
        include $this->filePath . '/' . $this->view . '.php';
        $midContent = ob_get_contents();
        ob_end_clean();

        $lines = explode(PHP_EOL, $midContent);
        foreach ($lines as $aLine) {
            if (substr($aLine, 0, 1) == '@') {
                $this->addMacroFromLine($lines['0']);
            } else {
                $this->raw .= $aLine;
            }
        }

        if (! empty($this->footer)) {
            ob_start();
            include $this->filePath . '/' . $this->footer . '.php';
            $this->raw .= ob_get_contents();
            ob_end_clean();
        }

        $this->addFinalMacros();

        $this->rendered = $this->performChanges($this->raw, $this->changes);

        return $this->rendered;
    }


    /**
     * @param $line
     */
    private function addMacroFromLine($line)
    {
        $exp = explode('=', substr($line, 1));

        $first = array_shift($exp);

        $this->changes['meta'][$first] = implode('=', $exp);
    }


    /**
     *
     */
    private function addFinalMacros()
    {
        $this->changes['meta']['theme'] = $this->theme;
        $this->changes['meta']['lang'] = $this->lang;
    }


    /**
     * @param $content
     * @param $changes
     * @param string $prefix
     *
     * @return mixed
     */
    private function performChanges($content, $changes, $prefix = '')
    {
        foreach ($changes as $item => $value) {
            if (is_array($value)) {
                $content = $this->performChanges($content, $value, $item);
            } else {
                if (! empty($prefix)) {
                    $use = $prefix . '.' . $item;
                } else {
                    $use = $item;
                }
                $content = str_replace('{{' . $use . '}}', $value, $content);
            }
        }
        return $content;
    }

}
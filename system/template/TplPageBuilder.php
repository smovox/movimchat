<?php

/**
 * @file TplPageBuilder.php
 * This file is part of Movim.
 * 
 * @brief This class is the templating engine for movim. It also handles themes.
 *
 * @author Timothée jaussoin
 *
 */

class TplPageBuilder
{
    //    internal variables
    private $theme = 'movim';
    private $title = '';
    private $menu = array();
    private $content = '';
    private $user;
    private $css = array();
    private $scripts = array();
    private $polling = true;

    /**
     * Constructor. Determines whether to show the login page to the user or the
     * Movim interface.
     */
    function __construct(&$user = NULL)
    {
        $this->user = $user;

        $cd = new \Modl\ConfigDAO();
        $config = $cd->get();
        $this->theme = $config->theme;
     
    }

    function viewsPath($file)
    {
        return VIEWS_PATH . '/' . $file;
    }

    /**
     * Returns or prints the link to a file.
     * @param file is the path to the file relative to the theme's root
     * @param return optionally returns the link instead of printing it if set to true
     */
    function linkFile($file, $return = false)
    {
        $path = BASE_URI . 'themes/' . $this->theme . '/' . $file;

        if($return) {
            return $path;
        } else {
            echo $path;
        }
    }

    /**
     * Inserts the link tag for a css file.
     */
    function themeCss($file)
    {
        echo '<link rel="stylesheet" href="'
            . $this->linkFile($file, true) .
            "\" type=\"text/css\" />\n";
    }
    
    /**
     * Actually generates the page from templates.
     */
    function build($template)
    {
        if (ENVIRONMENT === 'production') ob_clean();
        ob_start();

        require($this->viewsPath($template));
        $outp = ob_get_clean();
        $outp = str_replace('<%scripts%>',
                            $this->printCss() . $this->printScripts(),
                            $outp);

        return $outp;
    }

    /**
     * Sets the page's title.
     */
    function setTitle($name)
    {
        $this->title = $name;
    }

    /**
     * Displays the current title.
     */
    function title()
    {
        echo $this->title;
    }

    /**
     * Adds a link to the menu with the displayed label.
     */
    function menuAddLink($label, $href, $active = false, $mobile = false)
    {
        $this->menu[] = array(
            'type' => 'link',
            'label' => $label,
            'href' => $href,
            'active' => $active,
            'mobile' => $mobile
            );
    }

    function menuAddVerbatim($html)
    {
        $this->menu[] = array(
            'type' => 'verbatim',
            'html' => $html,
            );
    }

    /** shows up the menu. */
    function menu()
    {
        echo '<ul class="menu">' . "\n";
        foreach($this->menu as $link) {
            if($link['type'] == 'link') {
                echo "\t\t".'<li><a 
                    href="'.Route::urlize($link['href']).'" 
                    title="'.$link['label'].'"
                    class="'.$link['href'].'' ;
                if($link['active'] == true) {
                    echo ' active ';
                }
                // If we display only the link on desktop
                if($link['mobile'] == true) {
                    echo ' on_desktop ';
                }
                echo '"';
                echo "><span class=\"mobile\">".$link['label'] . "</span></a></li>\n";
            } else {
                echo $link['html'];
            }
        }
        echo "\t</ul>\n";
    }

    function addScript($script)
    {
        $this->scripts[] = BASE_URI . 'app/assets/js/' . $script;
    }

    /**
     * Inserts the link tag for a css file.
     */
    function addCss($file)
    {
        $this->css[] = $this->linkFile($file, true);
    }

    function scripts()
    {
        echo '<%scripts%>';
    }

    function printScripts() {
        $out = '';
        $widgets = WidgetWrapper::getInstance();
        $scripts = array_merge($this->scripts, $widgets->loadjs());
        foreach($scripts as $script) {
             $out .= '<script type="text/javascript" src="'
                 . $script .
                 '"></script>'."\n";
        }

        $ajaxer = AjaxController::getInstance();
        $out .= $ajaxer->genJs();

        return $out;
    }

    function printCss() {
        $out = '';
        $widgets = WidgetWrapper::getInstance();
        $csss = array_merge($this->css, $widgets->loadcss()); // Note the 3rd s, there are many.
        foreach($csss as $css_path) {
            $out .= '<link rel="stylesheet" href="'
                . $css_path .
                "\" type=\"text/css\" />\n";
        }
        return $out;
    }

    function setContent($data)
    {
        $this->content .= $data;
    }

    function addContent($data, $append = true)
    {
        if($append) {
            $this->content .= $data;
        } else {
            $this->content = $data . $this->content;
        }
    }

    function content()
    {
        echo $this->content;
    }

    /**
     * Loads up a widget and prints it at the current place.
     */
    function widget($name, $register = true)
    {
        $widgets = WidgetWrapper::getInstance($register);
        echo $widgets->runWidget($name, 'build');
    }
    
    function displayFooterDebug()
    {
        //\system\Logs\Logger::displayFooterDebug();
    }
}


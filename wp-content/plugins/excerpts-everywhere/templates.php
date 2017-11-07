<?php

function template_3($id, $title, $content, $the_image, $permalink, $options = array())
{
    $text = '<div class="post-teaser post-teaser-tmpl-' . $options['template'] .
            ' ' . $options['icon_size'] . ' ' . (($options['is_icon']) ? 'icon' : '') . 
            '" style="background-image: url(' . $options['src'] . ');">';

    $text .= '<div class="item" id="item-' . $id . '">
                      <div class="img">' . $the_image;                                     

    if ($title) {
        $h2 = '<h2 class="t-title"><a href="' . $permalink. '">' . $title . '</a></h2>';
    }
    $text .= $h2;
    $text .= '<div class="teaser">
        '. $content . '</div>
        <a class="read-more" title="More About ' . $title . '" href="' . $permalink . '">Read More</a>
             </div>
              </div>';    
    return $text;
}

function template_1($id, $title, $content, $the_image, $permalink, $options = array())
{
    $text = '<div class="post-teaser post-teaser-tmpl-' . $options['template'] .
            ' ' . $options['icon_size'] . ' ' . (($options['is_icon']) ? 'icon' : '') . 
            '" style="background-image: url(' . $options['src'] . ');">';

    $text .= '<div class="item" id="item-' . $id . '">
                      <div class="img">' . $the_image;                                     

    if ($title) {
        $h2 = '<h2 class="t-title"><a href="' . $permalink. '">' . $title . '</a></h2>';
    }
    $text .= $h2;
    $text .= '<div class="teaser">
        '. $content . '</div>
        <a class="read-more" title="More About ' . $title . '" href="' . $permalink . '">Read More</a>
             </div>
              </div>';    
    return $text;
}

function template_1_1($id, $title, $content, $the_image, $permalink, $options = array())
{
    $text = '<div class="post-teaser post-teaser-tmpl-' . $options['template'] . '">';
    $text .= '<div class="item" id="item-' . $id . '">
                      <div class="img">' . $the_image;                                     

    if ($title) {
        $h2 = '<h2 class="t-title"><a href="' . $permalink. '">' . $title . '</a></h2>';
    }
    $text .= $h2;
    $text .= '<div>
        '. $content . '</div>
        <a class="read-more" title="More About ' . $title . '" href="' . $permalink . '">Read More</a>
             </div>
              </div>';    
    return $text;
}

function template_2($id, $title, $content, $the_image, $permalink, $options = array())
{
    $text = '<div class="post-teaser post-teaser-tmpl-' . $options['template'] .
            ' ' . $options['icon_size'] . ' ' . (($options['is_icon']) ? 'icon' : '') . 
            '">';

    $text .= '<div class="item" id="item-' . $id . '">
                      <div class="img">';                                     

    if ($title) {
        $h2 = '<h2 class="t-title"><a href="' . $permalink. '">' . $title . '</a></h2>';
    }
    
    $text .= $h2;
    $text .= $the_image;
    $text .= '<div class="teaser" style="background-image: url(' . $options['src'] . ');">
        '. $content . '</div>
        <a class="read-more" title="More About ' . $title . '" href="' . $permalink . '">Read More</a>
             </div>
              </div>';    
    return $text;
}

function template_4($id, $title, $content, $the_image, $permalink, $options = array())
{
    $text = '<div class="post-teaser post-teaser-tmpl-' . $options['template'] .
            ' ' . $options['icon_size'] . ' ' . (($options['is_icon']) ? 'icon' : '') . 
            '">';

    $text .= '<div class="item" id="item-' . $id . '">
                      <div class="img">';                                     

    if ($title) {
        $h2 = '<h2 class="t-title"><a href="' . $permalink. '">' . $title . '</a></h2>';
    }
    
    $text .= $h2;
    $text .= $the_image;
    $text .= '<div class="teaser" style="background-image: url(' . $options['src'] . ');">
        '. $content . '</div>
        <a class="read-more" title="More About ' . $title . '" href="' . $permalink . '">Read More</a>
             </div>
              </div>';    
    return $text;
}
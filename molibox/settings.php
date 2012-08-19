<?php

function molibox_settings_api_init() {
  add_settings_section('molibox_settings', __('MoliBox', 'molibox'), 'molibox_setting_section_callback', 'media');
  
 	  add_settings_field('molibox_enable_always', __('Enable always', 'molibox'), 'molibox_setting_callback_enable_always', 'media', 'molibox_settings');
 	register_setting('media', 'molibox_enable_always');
  
 	  add_settings_field('molibox_rel_types', __('<code>rel</code> types', 'molibox'), 'molibox_setting_callback_rel_types', 'media', 'molibox_settings', array('label_for' => 'molibox_rel_types'));
 	register_setting('media', 'molibox_rel_types', 'molibox_sanitize_rel_types');
  
 	  add_settings_field('molibox_overlay_color', __('Overlay color', 'molibox'), 'molibox_setting_callback_overlay_color', 'media', 'molibox_settings', array('label_for' => 'molibox_overlay_color'));
 	register_setting('media', 'molibox_overlay_color', 'molibox_sanitize_overlay_color');
  
 	  add_settings_field('molibox_overlay_opacity', __('Overlay opacity', 'molibox'), 'molibox_setting_callback_overlay_opacity', 'media', 'molibox_settings', array('label_for' => 'molibox_overlay_opacity'));
 	register_setting('media', 'molibox_overlay_opacity', 'molibox_sanitize_overlay_opacity');
}

function molibox_setting_section_callback() {
  echo '<p>' . __('The MoliBox will open on image links and show the linked image enlarged. On mobile devices, there are also intuitive swipe gestures.', 'molibox') . '</p>';
}

function molibox_setting_callback_enable_always() {
 	  ?><input name="molibox_enable_always" id="molibox_enable_always" type="checkbox" <?php checked('on', get_option('molibox_enable_always')); ?> /> <label for="molibox_enable_always"><?php _e('Let all links linking to an image open a MoliBox. Else, they must have a <code>rel</code> attribute (specified in the next field).', 'molibox'); ?></label><?php
}

function molibox_setting_callback_rel_types() {
 	  ?><input name="molibox_rel_types" id="molibox_rel_types" type="text" value="<?php echo get_option('molibox_rel_types'); ?>" /><p class="description"><?php _e('If MoliBox is not always enabled, links must have a <code>rel</code> attribute containing at least one of these values (comma separated) to open a MoliBox.', 'molibox'); ?></p><?php
}
function molibox_sanitize_rel_types($input) {
  return preg_replace('/\s*,\s*/', ',', strip_tags($input));
}

function molibox_setting_callback_overlay_color() {
 	  ?><input name="molibox_overlay_color" id="molibox_overlay_color" type="color" value="<?php echo get_option('molibox_overlay_color'); ?>" required="required" /><p class="description"><?php printf(__('The color the screen turns to when opening MoliBox. Examples (all result in the same color): %1$s, %2$s, %3$s, %4$s, %5$s', 'molibox'), '<code>red</code>', '<code>#ff0000</code>', '<code>#f00</code>', '<code>rgb(255, 0 0)</code>', '<code>rgb(100%, 0%, 0%)</code>'); ?></p><?php
}
function molibox_sanitize_overlay_color($input) {
  $color = trim($input);
  
  $matches = array();
  
  // e.g. #00aa00
  if (preg_match('/^#[0-9a-f]{6}$/i', $color))
    return strtolower($color); // correct, don't convert
  
  // e.g. #0a0
  if (preg_match('/^#([0-9a-f])([0-9a-f])([0-9a-f])$/i', $color, $matches))
    return strtolower('#' . $matches[1] . $matches[1] . $matches[2] . $matches[2] . $matches[3] . $matches[3]);
  
  // e.g. rgb(27, 99, 255) or rgb(3%, 0%, 255%)
  if (preg_match('/^rgb\(([0-9]+%?),\s*([0-9]+%?),\s*([0-9]+%?)\)$/', $color, $matches)) {
    return molibox_rgb2hex(array($matches[1], $matches[2], $matches[3]));
  }
  
  // TODO: hsl
  
  $namedColors = array(
    'aliceblue' => '#f0f8f5',
    'antiquewhite' => '#faebd7',
    'aquamarine' => '#7fffd4',
    'aqua' => '#00ffff',
    'azure' => '#f0ffff',
    'beige' => '#f5f5dc',
    'bisque' => '#ffe4c4',
    'blanchedalmond' => '#ffebcd',
    'blue' => '#0000ff',
    'blueviolet' => '#8a2be2',
    'brown' => '#a52a2a',
    'burlywood' => '#deb823',
    'cadetblue' => '#5f9ea0',
    'chartreuse' => '#7fff00',
    'chocolate' => '#d2691e',
    'coral' => '#ff7f50',
    'cornflowerblue' => '#6495ed',
    'cornsilk' => '#fff8dc',
    'crimson' => '#dc143c',
    'darkblue' => '#00008b',
    'darkcyan' => '#008b8b',
    'darkgoldenrod' => '#b8860b',
    'darkgray' => '#a9a9a9',
    'darkgreen' => '#006400',
    'darkgrey' => '#a9a9a9',
    'darkkhaki' => '#bdb76b',
    'darkmagenta' => '#8b008b',
    'darkolivegreen' => '#556b2f',
    'darkorange' => '#ff8c00',
    'darkorchid' => '#9932cc',
    'darkred' => '#8b0000',
    'darksalmon' => '#e9967a',
    'darkseagreen' => '#8fbc8f',
    'darkslateblue' => '#483d8b',
    'darkslategray' => '#2f4f4f',
    'darkslategrey' => '#2f4f4f',
    'darkturquoise' => '#00ced1',
    'darkviolet' => '#9400d3',
    'deeppink' => '#ff1493',
    'deepskyblue' => '#00bfff',
    'dimgray' => '#696969',
    'dimgrey' => '#696969',
    'dodgerblue' => '#1e90ff',
    'firebrick' => '#b22222',
    'floralwhite' => '#fffaf0',
    'forestgreen' => '#228b22',
    'fuchsia' => '#ff00ff',
    'gainsboro' => '#dcdcdc',
    'ghostwhite' => '#f8f8ff',
    'goldenrod' => '#daa520',
    'gold' => '#ffd700',
    'gray' => '#808080',
    'green' => '#008000',
    'greenyellow' => '#adff2f',
    'grey' => '#808080',
    'honeydew' => '#f0fff0',
    'hotpink' => '#ff69b4',
    'indianred' => '#cd5c5c',
    'indigo' => '#4b0082',
    'ivory' => '#fffff0',
    'khaki' => '#f0e68c',
    'lavenderblush' => '#fff0f5',
    'lavender' => '#e6e6fa',
    'lawngreen' => '#7cfc00',
    'lemonchiffon' => '#fffacd',
    'lightblue' => '#add8e6',
    'lightcoral' => '#f08080',
    'lightcyan' => '#e0ffff',
    'lightgoldenrodyellow' => '#fafad2',
    'lightgray' => '#d3d3d3',
    'lightgreen' => '#90ee90',
    'lightgrey' => '#d3d3d3',
    'lightpink' => '#ffb6c1',
    'lightsalmon' => '#ffa07a',
    'lightseagreen' => '#20b2aa',
    'lightskyblue' => '#87cefa',
    'lightslategray' => '#778899',
    'lightslategrey' => '#778899',
    'lightsteelblue' => '#b0c4de',
    'lightyellow' => '#ffffe0',
    'limegreen' => '#32cd32',
    'lime' => '#00ff00',
    'linen' => '#faf0e6',
    'maroon' => '#800000',
    'mediumaquamarine' => '#66cdaa',
    'mediumblue' => '#0000cd',
    'mediumorchid' => '#ba55d3',
    'mediumpurple' => '#9370db',
    'mediumseagreen' => '#3cb371',
    'mediumslateblue' => '#7b68ee',
    'mediumspringgreen' => '#00fa9a',
    'mediumturquoise' => '#48d1cc',
    'mediumvioletred' => '#c71585',
    'midnightblue' => '#191970',
    'mintcream' => '#f5fffa',
    'mistyrose' => '#ffe4e1',
    'moccasin' => '#ffe4b5',
    'navajowhite' => '#ffdead',
    'navy' => '#000080',
    'oldlace' => '#fdf5e6',
    'olivedrab' => '#6b8e23',
    'olive' => '#808000',
    'orangered' => '#ff4500',
    'orange' => '#ffa500',
    'orchid' => '#da70d6',
    'palegoldenrod' => '#eee8aa',
    'palegreen' => '#98fb98',
    'paleturquoise' => '#afeeee',
    'palevioletred' => '#db7093',
    'papayawhip' => '#ffefd5',
    'peachpuff' => '#ffdab9',
    'peru' => '#cd853f',
    'pink' => '#ffc0cb',
    'plum' => '#dda0dd',
    'powderblue' => '#b0e0e6',
    'purple' => '#800080',
    'red' => '#ff0000',
    'rosybrown' => '#bc8f8f',
    'royalblue' => '#4169e1',
    'saddlebrown' => '#8b4513',
    'salmon' => '#fa8072',
    'sandybrown' => '#f4a460',
    'seagreen' => '#2e8b57',
    'seashell' => '#fff5ee',
    'sienna' => '#a0522d',
    'silver' => '#c0c0c0',
    'skyblue' => '#87ceeb',
    'slateblue' => '#6a5acd',
    'slategray' => '#708090',
    'slategrey' => '#708090',
    'snow' => '#fffafa',
    'springgreen' => '#00ff7f',
    'steelblue' => '#4682b4',
    'tan' => '#d2b48c',
    'teal' => '#008080',
    'thistle' => '#d8bfd8',
    'tomato' => '#ff6347',
    'turquoise' => '#40e0d0',
    'violet' => '#ee82ee',
    'wheat' => '#f5deb3',
    'white' => '#ffffff',
    'whitesmoke' => '#f5f5f5',
    'yellowgreen' => '#9acd32',
    'yellow' => '#ffff00',
  );
  
  // named colors
  if (array_key_exists($color, $namedColors)) {
    return $namedColors[$color];
  }
  
  // default
  return '#000000';
}

function molibox_rgb2hex($rgb) {
  $hex = '#';
  for ($i=0; $i<3; $i++) {
    if (strpos($rgb[$i], '%') !== false)
      $rgb[$i] = intval(substr($rgb[$i], 0, -1)) / 100 * 255;
    
    if ($rgb[$i] <= 0)
      $hex .= '00';
    elseif ($rgb[$i] >= 255)
      $hex .= 'ff';
    else
      $hex .= sprintf("%02x", round($rgb[$i]));
  }
  return $hex;
}

function molibox_setting_callback_overlay_opacity() {
 	  ?><input name="molibox_overlay_opacity" id="molibox_overlay_opacity" type="number" min="0" max="1" step="0.01" value="<?php echo get_option('molibox_overlay_opacity'); ?>" required="required" /><p class="description"><?php _e('0 means fully transparent, 1 means fully opaque.', 'molibox'); ?></p><?php
}
function molibox_sanitize_overlay_opacity($input) {
  $opacity = floatval(str_replace(',', '.', $input));
  if ($opacity < 0)
    $opacity = 0;
  elseif ($opacity > 1)
    $opacity = 1;
  return $opacity;
}

?>
<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-02
 * Time: 16:35
 */

namespace App\Infrastructure\HTMLPurifier;

use HTMLPurifier_AttrDef_HTML_FrameTarget;
use HTMLPurifier_Config;

/**
 * Class Html5Config
 *
 * @package App\Infrastructure\HTMLPurifier
 */
final class Html5Config
{
    private const REVISION = 201901021713;

    /**
     * @param HTMLPurifier_Config $parent
     * @param array                $settings
     * @return HTMLPurifier_Config
     */
    public static function create(HTMLPurifier_Config $parent, array $settings = []): HTMLPurifier_Config
    {
        $config = HTMLPurifier_Config::inherit($parent);
        $config->set('HTML.DefinitionID', __CLASS__);
        $config->set('HTML.DefinitionRev', self::REVISION);
        $config->loadArray($settings);

        $def = $config->maybeGetRawHTMLDefinition();
        if ($def) {
            $def->addElement('section', 'Block', 'Flow', 'Common');
            $def->addElement('nav', 'Block', 'Flow', 'Common');
            $def->addElement('article', 'Block', 'Flow', 'Common');
            $def->addElement('aside', 'Block', 'Flow', 'Common');
            $def->addElement('header', 'Block', 'Flow', 'Common');
            $def->addElement('footer', 'Block', 'Flow', 'Common');
            $def->addElement('main', 'Block', 'Flow', 'Common');
            $def->addElement('address', 'Block', 'Flow', 'Common');
            $def->addElement('hgroup', 'Block', 'Required: h1 | h2 | h3 | h4 | h5 | h6', 'Common');
            $def->addElement('figure', 'Block', 'Flow', 'Common');
            $def->addElement('figcaption', false, 'Flow', 'Common');
            $def->addElement('s', 'Inline', 'Inline', 'Common');
            $def->addElement('var', 'Inline', 'Inline', 'Common');
            $def->addElement('sub', 'Inline', 'Inline', 'Common');
            $def->addElement('sup', 'Inline', 'Inline', 'Common');
            $def->addElement('mark', 'Inline', 'Inline', 'Common');
            $def->addElement('wbr', 'Inline', 'Empty', 'Core');
            $def->addElement('ins', 'Block', 'Flow', 'Common', ['cite' => 'URI', 'datetime' => 'Text']);
            $def->addElement('del', 'Block', 'Flow', 'Common', ['cite' => 'URI', 'datetime' => 'Text']);
            $time           = $def->addElement(
                'time',
                'Inline',
                'Inline',
                'Common',
                ['datetime' => 'Text', 'pubdate' => 'Bool']
            );
            $time->excludes = ['time' => true];
            $def->addElement(
                'a',
                'Inline',
                'Flow',
                'Common',
                [
                    'download' => 'Text',
                    'hreflang' => 'Text',
                    'rel'      => 'Text',
                    'target'   => new HTMLPurifier_AttrDef_HTML_FrameTarget(),
                    'type'     => 'Text',
                ]
            );
            $def->addAttribute('img', 'srcset', 'Text');
            $def->addAttribute('img', 'sizes', 'Text');
            $def->addAttribute('iframe', 'allowfullscreen', 'Bool');

        }

        return $config;
    }
}

<?php
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\View\View;
use Cake\View\Helper\HtmlHelper;




/**
 * Boot helper
 */
class BootHelper extends HtmlHelper
{
    
    
     /**
     * Creates an HTML link.
     *
     * If $url starts with "http://" this is treated as an external link. Else,
     * it is treated as a path to controller/action and parsed with the
     * UrlHelper::build() method.
     *
     * If the $url is empty, $title is used instead.
     *
     * ### Options
     *
     * - `escape` Set to false to disable escaping of title and attributes.
     * - `escapeTitle` Set to false to disable escaping of title. Takes precedence
     *   over value of `escape`)
     * - `confirm` JavaScript confirmation message.
     *
     * @param string $title The content to be wrapped by <a> tags.
     * @param string|array|null $url Cake-relative URL or array of URL parameters, or
     *   external URL (starts with http://)
     * @param array $options Array of options and HTML attributes.
     * @return string An `<a />` element.
     * @link http://book.cakephp.org/3.0/en/views/helpers/html.html#creating-links
     */
    public function link($title, $url = null, array $options = [])
    {
        $escapeTitle = true;
        if ($url !== null) {
            $url = $this->Url->build($url);
        } else {
            $url = $this->Url->build($title);
            $title = htmlspecialchars_decode($url, ENT_QUOTES);
            $title = h(urldecode($title));
            $escapeTitle = false;
        }

        if (isset($options['escapeTitle'])) {
            $escapeTitle = $options['escapeTitle'];
            unset($options['escapeTitle']);
        } elseif (isset($options['escape'])) {
            $escapeTitle = $options['escape'];
        }

        if ($escapeTitle === true) {
            $title = h($title);
        } elseif (is_string($escapeTitle)) {
            $title = htmlentities($title, ENT_QUOTES, $escapeTitle);
        }

        $confirmMessage = null;
        if (isset($options['confirm'])) {
            $confirmMessage = $options['confirm'];
            unset($options['confirm']);
        }
        if ($confirmMessage) {
            $options['onclick'] = $this->_bootConfirm($confirmMessage, 'return true;', 'return false;', $options,$url);
        }

        $templater = $this->templater();
        return $templater->format('link', [
            'url' => $url,
            'attrs' => $templater->formatAttributes($options),
            'content' => $title
        ]);
    }
    /**
 * Returns a string to be used as onclick handler for confirm dialogs.
 *
 * @param string $message Message to be displayed
 * @param string $okCode Code to be executed after user chose 'OK'
 * @param string $cancelCode Code to be executed after user chose 'Cancel'
 * @param array $options Array of options
 * @return string onclick JS code
 */
	
        protected function _bootConfirm($message, $okCode, $cancelCode = '', $options = [],$url) {        
        $confirm = "bootbox.confirm('{$message}', function(result) {                     
                 if (result){
                    window.location.href='{$url}'
                         } 
                 });$cancelCode";
        // We cannot change the key here in 3.x, but the behavior is inverted in this case
        $escape = isset($options['escape']) && $options['escape'] === false;
        if ($escape) {
            $confirm = h($confirm);
        }
        return $confirm;
    }

     protected function _bootAlert($message, $okCode, $cancelCode = '', $options = array()) {
                $confirm = "bootbox.alert('{$message}', function() {                     
                 
                 });";
		if (isset($options['escape']) && $options['escape'] === false) {
			$confirm = h($confirm);
		}
		return $confirm;
                
	}
}

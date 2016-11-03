<?php
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\View\View;
use Cake\View\Helper\FormHelper;

/**
 * BootForm helper
 */
class BootFormHelper extends FormHelper
{

    
    /**
     * Creates an HTML link, but access the URL using the method you specify
     * (defaults to POST). Requires javascript to be enabled in browser.
     *
     * This method creates a `<form>` element. If you want to use this method inside of an
     * existing form, you must use the `block` option so that the new form is being set to
     * a view block that can be rendered outside of the main form.
     *
     * If all you are looking for is a button to submit your form, then you should use
     * `FormHelper::button()` or `FormHelper::submit()` instead.
     *
     * ### Options:
     *
     * - `data` - Array with key/value to pass in input hidden
     * - `method` - Request method to use. Set to 'delete' to simulate
     *   HTTP/1.1 DELETE request. Defaults to 'post'.
     * - `confirm` - Confirm message to show.
     * - `block` - Set to true to append form to view block "postLink" or provide
     *   custom block name.
     * - Other options are the same of HtmlHelper::link() method.
     * - The option `onclick` will be replaced.
     *
     * @param string $title The content to be wrapped by <a> tags.
     * @param string|array|null $url Cake-relative URL or array of URL parameters, or
     *   external URL (starts with http://)
     * @param array $options Array of HTML attributes.
     * @return string An `<a />` element.
     * @link http://book.cakephp.org/3.0/en/views/helpers/form.html#creating-standalone-buttons-and-post-links
     */
    public function postLink($title, $url = null, array $options = [])
    {
        $options += ['block' => null, 'confirm' => null];

        $requestMethod = 'POST';
        if (!empty($options['method'])) {
            $requestMethod = strtoupper($options['method']);
            unset($options['method']);
        }

        $confirmMessage = $options['confirm'];
        unset($options['confirm']);

        $formName = str_replace('.', '', uniqid('post_', true));
        $formOptions = [
            'name' => $formName,
            'style' => 'display:none;',
            'method' => 'post',
        ];
        if (isset($options['target'])) {
            $formOptions['target'] = $options['target'];
            unset($options['target']);
        }
        $templater = $this->templater();

        $restoreAction = $this->_lastAction;
        $this->_lastAction($url);

        $action = $templater->formatAttributes([
            'action' => $this->Url->build($url),
            'escape' => false
        ]);

        $out = $this->formatTemplate('formStart', [
            'attrs' => $templater->formatAttributes($formOptions) . $action
        ]);
        $out .= $this->hidden('_method', [
            'value' => $requestMethod,
            'secure' => static::SECURE_SKIP
        ]);
        $out .= $this->_csrfField();

        $fields = [];
        if (isset($options['data']) && is_array($options['data'])) {
            foreach (Hash::flatten($options['data']) as $key => $value) {
                $fields[$key] = $value;
                $out .= $this->hidden($key, ['value' => $value, 'secure' => static::SECURE_SKIP]);
            }
            unset($options['data']);
        }
        $out .= $this->secure($fields);
        $out .= $this->formatTemplate('formEnd', []);
        $this->_lastAction = $restoreAction;

        if ($options['block']) {
            if ($options['block'] === true) {
                $options['block'] = __FUNCTION__;
            }
            $this->_View->append($options['block'], $out);
            $out = '';
        }
        unset($options['block']);

        $url = '#';
        $onClick = 'document.' . $formName . '.submit();';
        if ($confirmMessage) {
            $options['onclick'] = $this->_bootConfirm($confirmMessage, $onClick, '', $options);
        } else {
            $options['onclick'] = $onClick . ' ';
        }
        $options['onclick'] .= 'event.returnValue = false; return false;';

        $out .= $this->Html->link($title, $url, $options);
        return $out;
    }
  protected function _bootConfirm($message, $okCode, $cancelCode = '', $options = array()) {
                $confirm = "bootbox.confirm('{$message}', function(result) {                     
                 if (result){
                     {$okCode}
                         }
                 });";
		if (isset($options['escape']) && $options['escape'] === false) {
			$confirm = h($confirm);
		}
		return $confirm;
                
	}
}

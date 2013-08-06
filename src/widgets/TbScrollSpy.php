<?php
/**
 *## TbScrollSpy class file.
 *
 * @author Christoffer Niska <ChristofferNiska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2012-
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php) 
 */

/**
 *## Bootstrap scrollspy widget.
 *
 * @see <http://twitter.github.com/bootstrap/javascript.html#scrollspy>
 *
 * @since 1.0.0
 * @package booster.widgets.supplementary
 */
class TbScrollSpy extends CWidget
{
	/**
	 * @var string the CSS selector for the scrollspy element. Defaults to 'body'.
	 */
	public $selector = 'body';

	/**
	 * @var string the CSS selector for the spying element.
	 */
	public $target;

	/**
	 * @var integer the scroll offset (in pixels).
	 */
	public $offset;
    
    /**
	 * @var boolean whether there will be an offset on viewing the content.
	 */
    public $clickOffset = false;

	/**
	 * @var array string[] the Javascript event handlers.
	 */
	public $events = array();

	/**
	 *### .run()
	 *
	 * Runs the widget.
	 */
	public function run()
	{
		$script = "jQuery('{$this->selector}').attr('data-spy', 'scroll');";

		if (isset($this->target)) {
			$script .= "jQuery('{$this->selector}').attr('data-target', '{$this->target}');";
		}

		if (isset($this->offset)) {
			$script .= "jQuery('{$this->selector}').attr('data-offset', '{$this->offset}');";
		}
        
        if($this->clickOffset) {
            $script .= "jQuery(document).ready(function() {
                jQuery('{$this->selector}').attr('style', 'padding-top: '+({$this->offset}-1)+'px');

                jQuery('{$this->target} ul li a').click(function(event) {
                    event.preventDefault();
                    window.location.hash = $(this).attr('href');

                    $($(this).attr('href'))[0].scrollIntoView();
                    scrollBy(0, -{$this->offset}+1);
                });
            });";
        }

		/** @var CClientScript $cs */
		$cs = Yii::app()->getClientScript();
		$cs->registerScript(__CLASS__ . '#' . $this->selector, $script, CClientScript::POS_BEGIN);

		foreach ($this->events as $name => $handler) {
			$handler = CJavaScript::encode($handler);
			$cs->registerScript(
				__CLASS__ . '#' . $this->selector . '_' . $name,
				"jQuery('{$this->selector}').on('{$name}', {$handler});"
			);
		}
	}
}


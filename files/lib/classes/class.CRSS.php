<?php
/**
 * Klasse um simplexml zu erweitern um cdata benutzen zu können
 * @author jonasmartinez at gmail dot com @php.net
 */
class SimpleXMLExtend extends SimpleXMLElement
{
  public function addCData($nodename,$cdata_text)
  {
    $node = $this->addChild($nodename); //Added a nodename to create inside the function
    $node = dom_import_simplexml($node);
    $no = $node->ownerDocument;
      /** @noinspection PhpUndefinedMethodInspection */
      $node->appendChild($no->createCDATASection($cdata_text));
  }
} 

/**
 * Klasse für den RSS-Feed
 * @author Saris
 */
class CRSS {
	protected $obj_xmlRss = '';
	
	public function __construct($bol_sendHeader=true,$bol_standardChannel=true,$str_encode='UTF-8')
	{
		if($bol_sendHeader)
		{
			header('Content-Type: text/xml');
			header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-type: text/xml; charset='.$str_encode, true);
		}
		if($bol_standardChannel)
		{
			$this->addChannel(array('rss_title'=>getsetting('rss_motd_title','LOTGD Webfeed'),'rss_description'=>getsetting('rss_motd_description',''),'rss_link'=>getsetting('rss_link','http://atrahor.de'),'lastBuildDate'=>gmdate('D, d M Y H:i:s').' GMT','generator'=>'LOTGD Dragonslayer Edition','rss_image'=>getsetting('rss_image','LOTGD Webfeed')));
		}
	}
	
	public function addChannel($arr_data=array())
	{
		$this->obj_xmlRss = new SimpleXMLExtend('<rss version="0.92"></rss>');
		$channel = $this->obj_xmlRss->addChild('channel');
		$channel->addChild('title', ($arr_data['rss_title']));
		$channel->addChild('description', ($arr_data['rss_description']));
		$channel->addChild('link', $arr_data['rss_link']);
		$channel->addChild('lastBuildDate', $arr_data['lastBuildDate']);
		$channel->addChild('generator', ($arr_data['generator']));
		//$image = $channel->addChild('image');
		//$image->addChild('url',$arr_data['rss_image']);
		//$image->addChild('title',$arr_data['rss_title']);
		//$image->addChild('link',$arr_data['rss_link']);
		//$image->addChild('description',$arr_data['rss_description']);
	}
	
	public function addNode($arr_data = array())
	{
		$child = $this->obj_xmlRss->channel->addChild('item');
		$child->addCData('title', (strip_appoencode($arr_data['title'],3,true)));
		$child->addChild('pubDate', gmdate('D, d M Y H:i:s',$arr_data['pubdate']).' GMT');
		$child->addCData('link', $arr_data['link']);
		if($arr_data['motdtype'] == 1)
		{ 
			$arr_motd = utf8_unserialize($arr_data['description']); $arr_data['description'] = $arr_motd['body']; 
		}
		$child->addCData('description', (strip_appoencode($arr_data['description'],3,true)));
		$child->addCData('category', $arr_data['category']);
	}	
	
	public function output($bol_returnAsString=false){
		if($bol_returnAsString)
		{
			return $this->obj_xmlRss->asXML();
		}
		else
		{
			echo $this->obj_xmlRss->asXML();
		}
	}
}
?>

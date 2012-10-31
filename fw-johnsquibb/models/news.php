<?php
/**
 * The News Model does the back-end heavy lifting for the News Controller
 */
class News_Model
{
	/**
	 * Array of articles. Array keys are titles, array values are corresponding
	 * articles.
	 */
	private $articles = array
	(
		//article 1
		'new' => array
		(
			'title' => 'New Website' ,
			'content' => 'Welcome to the site! We are glad to have you here.'
		)
		,
		//2
		'mvc' => array
		(
			'title' => 'PHP MVC Frameworks are Awesome!' ,
			'content' => 'It really is very easy. Take it from us!'
		)
		,
		//3
		'test' => array
		(
			'title' => 'Testing' ,
			'content' => 'This is just a measly test article.'
		)
	);
	
	public function __construct()
	{
	}
	
	/**
	 * Fetches article based on supplied name
	 * 
	 * @param string $articleName
	 * 
	 * @return array $article
	 */
	public function get_article($articleName)
	{
		//fetch article from array
		//$article = $this->articles[$articleName];
		
		//return $article;
		return 'abcdefg';
	}
	
}

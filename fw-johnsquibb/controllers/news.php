<?php
/**
 * This file handles the retrieval and serving of news articles
 */
class News_Controller
{
	/**
	 * This template variable will hold the 'view' portion of our MVC for this 
	 * controller
	 */
	public $template = 'news';
	
	/**
	 * This is the default function that will be called by router.php
	 * 
	 * @param array $getVars the GET variables posted to index.php
	 */
	public function main(array $getVars)
	{
//print_r($getVars);die;

		$newsModel = new News_Model;

//print_r($newsModel);die;
		
		//get an article
		$article = $newsModel->get_article($getVars['article']);
		
		//create a new view and pass it our template
		$view = new View_Model($this->template);
		
		//assign article data to view
		$view->assign('title' , $article['title']);
		$view->assign('content' , $article['content']);
	}
}

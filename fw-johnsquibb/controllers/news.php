<?php
/**
 * This file handles the retrieval and serving of news articles
 */
class News_Controller
{
	
	public $template = 'news';
	

	public function main(array $getVars)
	{
new dBug($getVars);

		$newsModel = new News_Model;

new dBug($newsModel);
		
		//get an article
		$article = $newsModel->get_article($getVars['article']);
		
		//create a new view and pass it our template
		$view = new View_Model($this->template);
		
		//assign article data to view
		$view->assign('title' , $article['title']);
		$view->assign('content' , $article['content']);
	}
}

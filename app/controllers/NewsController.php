<?php

class NewsController extends \BaseController
{
	public function index()
	{
		$news = News::orderBy('createdAt', 'desc')
			->paginate(10);
			
		return View::make('index')
			->with('news', $news);
	}
}
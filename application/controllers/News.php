<?php

class News extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model( 'news_model' );
		$this->load->helper( 'url_helper' );
	}

	public function index() {
		$data['title'] = "Smart Grid";
		// Load the SmartGrid Library

		//Configs

		$config = array(
			"page_size"        => 10,
			"grid_name"        => "sg_1",
			"toolbar_position" => 'both'
		);

		$this->load->library( 'SmartGrid/Smartgrid', $config );

		// MySQL Query to get data
		$sql = "SELECT * FROM album";

		// Column settings
		$columns = array(
			"title"  => array( "header" => "Title", "type" => "label" ),
			"artist" => array( "header" => "Artist", "type" => "label" )
		);

		// Set the grid
		$this->smartgrid->set_grid( $sql, $columns );


		// Render the grid and assign to data array, so it can be print to on the view
		$data['grid_html'] = $this->smartgrid->render_grid();

		$this->load->view( 'templates/header', $data );
		$this->load->view( 'news/index', $data );
		$this->load->view( 'templates/footer' );
	}

	public function view( $slug = null ) {
		$data['news_item'] = $this->news_model->get_news( $slug );

		if ( empty( $data['news_item'] ) ) {
			show_404();
		}

		$data['title'] = $data['news_item']['title'];

		$this->load->view( 'templates/header', $data );
		$this->load->view( 'news/view', $data );
		$this->load->view( 'templates/footer' );
	}

	public function create() {
		$this->load->helper( 'form' );
		$this->load->library( 'form_validation' );

		$data['title'] = 'Create a news item';

		$this->form_validation->set_rules( 'title', 'Title', 'required' );
		$this->form_validation->set_rules( 'text', 'Text', 'required' );

		if ( $this->form_validation->run() === false ) {
			$this->load->view( 'templates/header', $data );
			$this->load->view( 'news/create' );
			$this->load->view( 'templates/footer' );

		} else {
			$this->news_model->set_news();
			$this->load->view( 'news/index' );
		}
	}
}

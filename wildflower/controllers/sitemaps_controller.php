<?php
class SitemapsController extends AppController {

	public $name = 'Sitemaps';
	public $helpers = array('Html', 'Form', 'PagesList');
	public $uses = array('Page');

	function index() {
		$pages = $this->Page->findAll(null, array('id', 'title', 'url', 'lft', 'rght', 'level'), 'Page.lft ASC');
		$this->set('pages', $pages);
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash('Invalid Sitemap.');
			$this->redirect(array('action'=>'index'), null, true);
		}
		$this->set('sitemap', $this->Sitemap->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->cleanUpFields();
			$this->Sitemap->create();
			if ($this->Sitemap->save($this->data)) {
				$this->Session->setFlash('The Sitemap has been saved');
				$this->redirect(array('action'=>'index'), null, true);
			} else {
				$this->Session->setFlash('The Sitemap could not be saved. Please, try again.');
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash('Invalid Sitemap');
			$this->redirect(array('action'=>'index'), null, true);
		}
		if (!empty($this->data)) {
			$this->cleanUpFields();
			if ($this->Sitemap->save($this->data)) {
				$this->Session->setFlash('The Sitemap has been saved');
				$this->redirect(array('action'=>'index'), null, true);
			} else {
				$this->Session->setFlash('The Sitemap could not be saved. Please, try again.');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Sitemap->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash('Invalid id for Sitemap');
			$this->redirect(array('action'=>'index'), null, true);
		}
		if ($this->Sitemap->del($id)) {
			$this->Session->setFlash('Sitemap #'.$id.' deleted');
			$this->redirect(array('action'=>'index'), null, true);
		}
	}


	function admin_index() {
		$this->Sitemap->recursive = 0;
		$this->set('sitemaps', $this->paginate());
	}

	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash('Invalid Sitemap.');
			$this->redirect(array('action'=>'index'), null, true);
		}
		$this->set('sitemap', $this->Sitemap->read(null, $id));
	}

	function admin_add() {
		// Parent nodes select box data
		$parentNodes = $this->Sitemap->getSelectBoxData(null, 'loc');
		$this->set('parentNodes', $parentNodes);
		
		if (!empty($this->data)) {
			
			// Make location absolute
			$_loc = $this->data['Sitemap']['loc'];
			if ($_loc[0] != '/') {
				$_loc = "/$_loc";
			}
			$this->data['Sitemap']['loc'] = 'http://' . $_SERVER['SERVER_NAME'] . $this->base . $_loc;
			
			$this->cleanUpFields();
			$this->Sitemap->create();
			if ($this->Sitemap->save($this->data)) {
				$this->Session->setFlash('The Sitemap has been saved');
				$this->redirect(array('action'=>'index'), null, true);
			} else {
				$this->Session->setFlash('The Sitemap could not be saved. Please, try again.');
			}
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash('Invalid Sitemap');
			$this->redirect(array('action'=>'index'), null, true);
		}
		if (!empty($this->data)) {
			$this->cleanUpFields();
			if ($this->Sitemap->save($this->data)) {
				$this->Session->setFlash('The Sitemap has been saved');
				$this->redirect(array('action'=>'index'), null, true);
			} else {
				$this->Session->setFlash('The Sitemap could not be saved. Please, try again.');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Sitemap->read(null, $id);
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash('Invalid id for Sitemap');
			$this->redirect(array('action'=>'index'), null, true);
		}
		if ($this->Sitemap->del($id)) {
			$this->Session->setFlash('Sitemap #'.$id.' deleted');
			$this->redirect(array('action'=>'index'), null, true);
		}
	}

}
?>
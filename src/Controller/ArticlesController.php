<?php

namespace App\Controller;
use App\Controller\AppController;

class ArticlesController extends AppController {

	public function initialize() {
		parent::initialize();

		$this->Auth->allow(['tags']);

		$this->loadComponent('Paginator');
		$this->loadComponent('Flash');
	}

	public function index() {
		// removed redundant paginator component
		$articles = $this->Paginator->paginate($this->Articles->find());
		$this->set(compact('articles'));
	}

	public function view($slug = null) {
		try {
			$article = $this->Articles->findBySlug($slug)->contain(['Tags'])->firstOrFail();
			$this->set(compact('article'));	
		} catch (\Exception $e) {
			// Catch: NotFoundException, Log Exception & slug, flash error message and redirect to index or other page
			$this->Flash->error(__('Unable to find the selected article.'));
			return $this->redirect(['action' => 'index']);
		}
	}

	/**
	 * Set tags list
	 * @param none
	 * 
	 */
	private function _setTags() {
		$tags = $this->Articles->Tags->find('list');
		$this->set('tags', $tags);
	}

	public function add() {
		$article = $this->Articles->newEntity();
		if ($this->request->is('post')) {
			$article = $this->Articles->patchEntity($article, $this->request->getData());
			// Set the user_id from the session.
			$article->user_id = $this->Auth->user('id');

			if ($this->Articles->save($article)) {
				$this->Flash->success(__('Your article has been saved.'));
				return $this->redirect(['action' => 'index']);
			}
			$this->Flash->error(__('Unable to add your article.'));
		}

		$this->_setTags();
		$this->set('article', $article);
	}

	public function edit($slug = null) {
		try {
			$article = $this->Articles->
				findBySlug($slug)
				->contain(['Tags'])
				->firstOrFail();
			if ($this->request->is(['post', 'put'])) {
				$this->Articles->patchEntity($article, $this->request->getData(), [
					// Disable modification of user_id
					'accessibleField' => ['user_id' => false],
				]);
				if ($this->Articles->save($article)) {
		            $this->Flash->success(__('Your article has been updated.'));
		            return $this->redirect(['action' => 'index']);
		        }
		        $this->Flash->error(__('Unable to update your article.'));
			}
		} catch (\Exception $e) {
			// Catch: NotFoundException, Log Exception & slug, flash error message and redirect to index or other page
			$this->Flash->error(__('Unable to find the selected article.'));
			return $this->redirect(['action' => 'index']);
		}

		$this->_setTags();
		$this->set('article', $article);
		$this->render('add'); // we can use add view instead of creating new view file
	}

	public function delete($slug = null) {
		try {
			$this->request->allowMethod(['post', 'slug']);
			$article = $this->Articles->findBySlug($slug)->firstOrFail();
			if ($this->Articles->delete($article)) {
				$this->Flash->succes(__('The {0} article has been deleted.', $article->title));
				return $this->redirect(['action' => 'index']);
			}
		} catch (\Exception $e) {
			// Catch: NotFoundException and request method, Log Exception & slug, flash error message and redirect to index or other page
			$this->Flash->error(__('Unable to delete selected article.'));
			return $this->redirect(['action' => 'index']);
		}
	}

	public function tags(...$tags) {
		// TO DO: Check if tags exist
		$articles = $this->Articles->find('tagged', [
			'tags' => $tags,
		]);

		// TO DO: Check if articles are not empty
		$this->set(compact('articles', 'tags'));
	}

	public function isAuthorized($user) {
		$action = $this->request->getParam('action');
		// The add and tags actions are always allowed to logged in users.
	    if (in_array($action, ['add', 'tags'])) {
	        return true;
	    }

	    // All other actions require a slug.
	    $slug = $this->request->getParam('pass.0');
	    if (!$slug) {
	        return false;
	    }

	    // Check that the article belongs to the current user.
    	$article = $this->Articles->findBySlug($slug)->first();
    	// debug($article->user_id === $user['id']); die;
    	return $article->user_id === $user['id'];
	}
}

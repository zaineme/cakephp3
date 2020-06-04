<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    public function initialize() {
        parent::initialize();
        $this->Auth->allow(['logout', 'add']);
    }

    public function login() {
        $redirectUrl = '/articles'; // default login url
        // Google Login here
        if ($this->request->is('ajax')) {
            return $this->_googleLogin();
        }

        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                if($this->Auth->redirectUrl() !== '/') {
                    $redirectUrl = $this->Auth->redirectUrl();
                }
                return $this->redirect($redirectUrl);
            }
            $this->Flash->error('Your username or password is incorrect.');
        }

        $this->set('redirectUrl', $redirectUrl);
        $this->layout = 'no-sidebar';
    }

    public function _googleLogin() {
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $email = $this->request->getData('email');
            if ($email) {
                $message = ['success' => false];
                $user = $this->Users->find('all')
                    ->where(['email' => $email])
                    ->first();
                if (empty($user)) {
                    // If there's no existing user, create one
                    $user = $this->Users->newEntity();
                    $user = $this->Users->patchEntity($user, [
                        'email' => $email,
                        'password' => uniqid(), // set random password
                    ]);
                    $this->Users->save($user);
                }
                if (!empty($user)) {
                    $this->loadComponent('Auth');
                    $auth = $this->Auth->setUser($user->toArray());
                    // Authenticate existing user
                    $message['success'] = true;
                }
                return $this->response->withType("application/json")->withStringBody(json_encode($message));
            }
        }
    }

    public function logout() {
        $this->Flash->success('You are now logged out.');
        return $this->redirect($this->Auth->logout());
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Articles'],
        ]);

        $this->set('user', $user);
        $this->layout = 'no-sidebar';
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {   
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function isAuthorized($user) {
        $action = $this->request->getParam('action');

        // All other actions require an id.
        $id = $this->request->getParam('pass.0');
        if (!$id) {
            return false;
        }

        if (in_array($action, ['edit'])) {
            $currentUser = $this->Users->get($id);
            return $currentUser->id === $user['id'];
        }

        return false;
    }
}

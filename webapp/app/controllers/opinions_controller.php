#<?php
#class OpinionsController extends AppController {

#	var $name = 'Opinions';
#	var $helpers = array('Html', 'Form');

#	function index() {
#		$this->Opinion->recursive = 0;
#		$this->set('opinions', $this->paginate());
#	}

#	function view($id = null) {
#		if (!$id) {
#			$this->Session->setFlash(__('Invalid Opinion.', true));
#			$this->redirect(array('action'=>'index'));
#		}
#		$this->set('opinion', $this->Opinion->read(null, $id));
#	}

#	function add() {
#		if (!empty($this->data)) {
#			$this->Opinion->create();
#			if ($this->Opinion->save($this->data)) {
#				$this->Session->setFlash(__('The Opinion has been saved', true));
#				$this->redirect(array('action'=>'index'));
#			} else {
#				$this->Session->setFlash(__('The Opinion could not be saved. Please, try again.', true));
#			}
#		}
#		$twitts = $this->Opinion->Twitt->find('list');
#		$this->set(compact('twitts'));
#	}

#	function edit($id = null) {
#		if (!$id && empty($this->data)) {
#			$this->Session->setFlash(__('Invalid Opinion', true));
#			$this->redirect(array('action'=>'index'));
#		}
#		if (!empty($this->data)) {
#			if ($this->Opinion->save($this->data)) {
#				$this->Session->setFlash(__('The Opinion has been saved', true));
#				$this->redirect(array('action'=>'index'));
#			} else {
#				$this->Session->setFlash(__('The Opinion could not be saved. Please, try again.', true));
#			}
#		}
#		if (empty($this->data)) {
#			$this->data = $this->Opinion->read(null, $id);
#		}
#		$twitts = $this->Opinion->Twitt->find('list');
#		$this->set(compact('twitts'));
#	}

#	function delete($id = null) {
#		if (!$id) {
#			$this->Session->setFlash(__('Invalid id for Opinion', true));
#			$this->redirect(array('action'=>'index'));
#		}
#		if ($this->Opinion->del($id)) {
#			$this->Session->setFlash(__('Opinion deleted', true));
#			$this->redirect(array('action'=>'index'));
#		}
#	}


#	function admin_index() {
#		$this->Opinion->recursive = 0;
#		$this->set('opinions', $this->paginate());
#	}

#	function admin_view($id = null) {
#		if (!$id) {
#			$this->Session->setFlash(__('Invalid Opinion.', true));
#			$this->redirect(array('action'=>'index'));
#		}
#		$this->set('opinion', $this->Opinion->read(null, $id));
#	}

#	function admin_add() {
#		if (!empty($this->data)) {
#			$this->Opinion->create();
#			if ($this->Opinion->save($this->data)) {
#				$this->Session->setFlash(__('The Opinion has been saved', true));
#				$this->redirect(array('action'=>'index'));
#			} else {
#				$this->Session->setFlash(__('The Opinion could not be saved. Please, try again.', true));
#			}
#		}
#		$twitts = $this->Opinion->Twitt->find('list');
#		$this->set(compact('twitts'));
#	}

#	function admin_edit($id = null) {
#		if (!$id && empty($this->data)) {
#			$this->Session->setFlash(__('Invalid Opinion', true));
#			$this->redirect(array('action'=>'index'));
#		}
#		if (!empty($this->data)) {
#			if ($this->Opinion->save($this->data)) {
#				$this->Session->setFlash(__('The Opinion has been saved', true));
#				$this->redirect(array('action'=>'index'));
#			} else {
#				$this->Session->setFlash(__('The Opinion could not be saved. Please, try again.', true));
#			}
#		}
#		if (empty($this->data)) {
#			$this->data = $this->Opinion->read(null, $id);
#		}
#		$twitts = $this->Opinion->Twitt->find('list');
#		$this->set(compact('twitts'));
#	}

#	function admin_delete($id = null) {
#		if (!$id) {
#			$this->Session->setFlash(__('Invalid id for Opinion', true));
#			$this->redirect(array('action'=>'index'));
#		}
#		if ($this->Opinion->del($id)) {
#			$this->Session->setFlash(__('Opinion deleted', true));
#			$this->redirect(array('action'=>'index'));
#		}
#	}

#}
#?>

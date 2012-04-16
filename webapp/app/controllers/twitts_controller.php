#<?php
#class TwittsController extends AppController {

#	var $name = 'Twitts';
#	var $helpers = array('Html', 'Form');

#	function index() {
#		$this->Twitt->recursive = 0;
#		$this->set('twitts', $this->paginate());
#	}

#	function view($id = null) {
#		if (!$id) {
#			$this->Session->setFlash(__('Invalid Twitt.', true));
#			$this->redirect(array('action'=>'index'));
#		}
#		$this->set('twitt', $this->Twitt->read(null, $id));
#	}

#	function add() {
#		if (!empty($this->data)) {
#			$this->Twitt->create();
#			if ($this->Twitt->save($this->data)) {
#				$this->Session->setFlash(__('The Twitt has been saved', true));
#				$this->redirect(array('action'=>'index'));
#			} else {
#				$this->Session->setFlash(__('The Twitt could not be saved. Please, try again.', true));
#			}
#		}
#	}

#	function edit($id = null) {
#		if (!$id && empty($this->data)) {
#			$this->Session->setFlash(__('Invalid Twitt', true));
#			$this->redirect(array('action'=>'index'));
#		}
#		if (!empty($this->data)) {
#			if ($this->Twitt->save($this->data)) {
#				$this->Session->setFlash(__('The Twitt has been saved', true));
#				$this->redirect(array('action'=>'index'));
#			} else {
#				$this->Session->setFlash(__('The Twitt could not be saved. Please, try again.', true));
#			}
#		}
#		if (empty($this->data)) {
#			$this->data = $this->Twitt->read(null, $id);
#		}
#	}

#	function delete($id = null) {
#		if (!$id) {
#			$this->Session->setFlash(__('Invalid id for Twitt', true));
#			$this->redirect(array('action'=>'index'));
#		}
#		if ($this->Twitt->del($id)) {
#			$this->Session->setFlash(__('Twitt deleted', true));
#			$this->redirect(array('action'=>'index'));
#		}
#	}


#	function admin_index() {
#		$this->Twitt->recursive = 0;
#		$this->set('twitts', $this->paginate());
#	}

#	function admin_view($id = null) {
#		if (!$id) {
#			$this->Session->setFlash(__('Invalid Twitt.', true));
#			$this->redirect(array('action'=>'index'));
#		}
#		$this->set('twitt', $this->Twitt->read(null, $id));
#	}

#	function admin_add() {
#		if (!empty($this->data)) {
#			$this->Twitt->create();
#			if ($this->Twitt->save($this->data)) {
#				$this->Session->setFlash(__('The Twitt has been saved', true));
#				$this->redirect(array('action'=>'index'));
#			} else {
#				$this->Session->setFlash(__('The Twitt could not be saved. Please, try again.', true));
#			}
#		}
#	}

#	function admin_edit($id = null) {
#		if (!$id && empty($this->data)) {
#			$this->Session->setFlash(__('Invalid Twitt', true));
#			$this->redirect(array('action'=>'index'));
#		}
#		if (!empty($this->data)) {
#			if ($this->Twitt->save($this->data)) {
#				$this->Session->setFlash(__('The Twitt has been saved', true));
#				$this->redirect(array('action'=>'index'));
#			} else {
#				$this->Session->setFlash(__('The Twitt could not be saved. Please, try again.', true));
#			}
#		}
#		if (empty($this->data)) {
#			$this->data = $this->Twitt->read(null, $id);
#		}
#	}

#	function admin_delete($id = null) {
#		if (!$id) {
#			$this->Session->setFlash(__('Invalid id for Twitt', true));
#			$this->redirect(array('action'=>'index'));
#		}
#		if ($this->Twitt->del($id)) {
#			$this->Session->setFlash(__('Twitt deleted', true));
#			$this->redirect(array('action'=>'index'));
#		}
#	}

#}
#?>

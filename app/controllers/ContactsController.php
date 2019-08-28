<?php 

use Phalcon\Mvc\Controller;
use Symfony\Component\Filesystem\Filesystem;

class ContactsController extends Controller 
{
	public function indexAction()
	{
		$this->view->contacts = Contacts::find(array("order" => "name"));
		$this->view->title = "Meus contatos" ;
		$this->view->citys = Citys::find(array("order" => "name"));
	}

	// for rendering the new contact form
	public function newAction()
	{
	}

	// for rendering the edit contact form
	public function editAction($id)
	{
		// The following if block is commented because
		// we use dispatcher->forward() method in Update Action
		// and forward method will trigger this check and
		// consider it as an invalid request.
		// So we don't need it in this case.


		// if (!$this->request->isPost()) {

		$contact = Contacts::findFirst($id);
		if(!$contact){
			$this->flash->error("Don’t try be smart and edit an invalid contact.");
			$this->dispatcher->forward(['action' => 'index']);
		}
		else {
			$this->tag->displayTo("id", $contact->id);
			$this->tag->displayTo("name", $contact->name);
			$this->tag->displayTo("email", $contact->email);
			$this->tag->displayTo("phone", $contact->phone);
			$this->tag->displayTo("age", $contact->age);
		}

		// }
		// else {
		// 	$this->flash->error("Invalid Request!!!");
		// 	$this->dispatcher->forward(['action' => 'index']);
		// }
	}

	// for creating a new contact in db
	public function createAction()
	{
		$contact = new Contacts();

		
		if ($this->request->hasFiles() == true) {
			$baseLocation = 'C:/xampp/htdocs/img/';

			$img = $this->request->getUploadedFiles()[0];
			$img->moveTo($baseLocation . $img->getName());

			$teste = $this->request->getPost();
			$teste['img'] = $img->getName();

			$success = $contact->save($teste, array('img', 'name', 'phone', 'email', 'age'));
			

			if ($success) {
				$this->flash->success("Contato Salvo com Sucesso!");
				$this->dispatcher->forward(['action' => 'index']);
			}
			else {
				$this->flash->error("Algo deu errado! :(  : <br/>");

				foreach ($contact->getMessages() as $message) {
					$this->flash->error($message);
				}

				$this->dispatcher->forward(['action' => 'new']);
			}
		}
		
	}

	// for updating
	public function updateAction()
	{
		if(!$this->request->isPost()){

			$this->flash->error("Requisição Inválida!!!");
		}
		
		else {

			if ($this->request->hasFiles() == true) {
				$baseLocation = 'C:/xampp/htdocs/img/';
	
				$img = $this->request->getUploadedFiles()[0];
				$img->moveTo($baseLocation . $img->getName());
	
				$teste = $this->request->getPost();
				$teste['img'] = $img->getName();

				$id = $this->request->getPost("id");
				$contact = Contacts::findFirst($id);

				if(!$contact){
					$this->flash->error("Nenhum registro encontrado");
				}
				else {
					$success = $contact->save($this->request->getPost(), array('img', 'name', 'phone', 'email', 'age'));

					if (!$success) {
						$this->flash->error("Following Errors occurred: <br/>");

						foreach ($contact->getMessages() as $message) {
							$this->flash->error($message);
						}

						return $this->dispatcher->forward(array(
							"action" => "edit",
							"params" => array($contact->id)
						));
					}

					$this->flash->success("Contact Successfully Updated!");
				}

			}
		}

		$this->dispatcher->forward(['action' => 'index']);						
	}	

	// for removing a contact
	public function deleteAction($id)
	{
		$contact = Contacts::findFirst($id);

		if(!$contact){
			$this->flash->error("Don’t try to remove a contact that doesn’t even exist in the first please.");
		}
		else {
			if(!$contact->delete()){
				
				foreach ($contact->getMessages() as $message) {
					$this->flash->error($message);
				}
			}
			else{
				$this->flash->success("The Contact R.I.P successful!!!");
			}

		}

		$this->dispatcher->forward(['action' => 'index']);
	}



	public function uploadActionImage()
    {
        // Check if the user has uploaded files
        if ($this->request->hasFiles() == true) {
            $baseLocation = 'files/';

            // Print the real file names and sizes
            foreach ($this->request->getUploadedFiles() as $file) {
                $photos = new Photo();              
                $photos->name = $file->getName();
                $photos->size = $file->getSize();
                $photos->save();

                //Move the file into the application
                $file->moveTo($baseLocation . $file->getName());
            }
        }
    }


}
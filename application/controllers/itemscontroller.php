<?php
 
class ItemsController extends Controller {
 
    /**
     * View a single item in our to do list
     * @param  int $id   id of the item 
     * @param  string $name name of the person using this to do list
     */
    function view($id = null,$name = null) {
        // Set the title of the page
            $this->set('title', $name.' - My Todo List App');

        // Get the item from the database and set as the to do
            $this->set('todo', $this->Item->select($id));
    }
     
    /**
     * View all items in our to do list
     */
    function viewall() {
        $this->set('title','All Items - My Todo List App');
        $this->set('todo', $this->Item->selectAll());
    }
     
    /**
     * Add an item to our database for the to do list
     */
    function add() {
        $todo = $_POST['todo'];
        $this->set('title','Success - My Todo List App');
        $query = 'INSERT INTO items (item_name) VALUES (\''.mysql_real_escape_string($todo).'\')';
        $this->set('todo', $this->Item->query($query));
    }
     
    /**
     * Delete an item from our to do list
     * @param  int $id id of the item
     */
    function delete($id = null) {
        $this->set('title','Success - My Todo List App');
        $query = 'DELETE FROM items WHERE id = \''.mysql_real_escape_string($id).'\'';
        $this->set('todo',$this->Item->query($query));   
    }
 
}
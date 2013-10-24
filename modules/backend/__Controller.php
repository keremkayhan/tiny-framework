<?php if ( ! defined('ACCESSIBLE') ) exit('NOT ACCESSIBLE');
/**
 * TINY 
 * 
 * A simple application development framework for PHP 5
 * 
 * @package 	Tiny
 * @author		Kerem Kayhan <keremkayhan@gmail.com>
 * @license		http://opensource.org/licenses/GPL-3.0 GNU General Public License
 * @copyright	2010-2013 Kerem Kayhan
 * @version		1.0
 */ 

class Controller extends BaseController
{

  public function index($request = null)
  {
  }  
  
  /* GENERATED ACTIONS */

  public function ank_soru($request = null)
  {
    $this->fields = Database::getTable('ank_soru')->findColumns();
    array_shift($this->fields);
    
    $this->items = Database::getTable('ank_soru')->findAll(array());
    $this->request = $request;
    
    if( $request->action && $request->action == 'delete' ){
      Database::getTable('ank_soru')->delete($request->id);
      $this->redirectReferer($request);
    }
    
    if( $request->action && $request->action == 'edit' ){
      $this->record = Database::getTable('ank_soru')->find($request->id);
      if( $request->isPost() ){
        $c = new Condition();
        foreach ($request->ank_soru as $key => $val){
          $c->add($key, $val);
        }
        Database::getTable('ank_soru')->save($c);
        $this->redirect('backend/ank_soru');
      }
    }

    if( $request->action && $request->action == 'new' ){
      if( $request->isPost() ){
        $c = new Condition();
        foreach ($request->ank_soru as $key => $val){
          $c->add($key, $val);
        }
        Database::getTable('ank_soru')->save($c);
        $this->redirect('backend/ank_soru');
      }
    }     
  }
  public function ank_cevap($request = null)
  {
    $this->fields = Database::getTable('ank_cevap')->findColumns();
    array_shift($this->fields);
    
    $this->items = Database::getTable('ank_cevap')->findAll(array());
    $this->request = $request;
    
    if( $request->action && $request->action == 'delete' ){
      Database::getTable('ank_cevap')->delete($request->id);
      $this->redirectReferer($request);
    }
    
    if( $request->action && $request->action == 'edit' ){
      $this->record = Database::getTable('ank_cevap')->find($request->id);
      if( $request->isPost() ){
        $c = new Condition();
        foreach ($request->ank_cevap as $key => $val){
          $c->add($key, $val);
        }
        Database::getTable('ank_cevap')->save($c);
        $this->redirect('backend/ank_cevap');
      }
    }

    if( $request->action && $request->action == 'new' ){
      if( $request->isPost() ){
        $c = new Condition();
        foreach ($request->ank_cevap as $key => $val){
          $c->add($key, $val);
        }
        Database::getTable('ank_cevap')->save($c);
        $this->redirect('backend/ank_cevap');
      }
    }     
  }
  public function ank_secim($request = null)
  {
    $this->fields = Database::getTable('ank_secim')->findColumns();
    array_shift($this->fields);
    
    $this->items = Database::getTable('ank_secim')->findAll(array());
    $this->request = $request;
    
    if( $request->action && $request->action == 'delete' ){
      Database::getTable('ank_secim')->delete($request->id);
      $this->redirectReferer($request);
    }
    
    if( $request->action && $request->action == 'edit' ){
      $this->record = Database::getTable('ank_secim')->find($request->id);
      if( $request->isPost() ){
        $c = new Condition();
        foreach ($request->ank_secim as $key => $val){
          $c->add($key, $val);
        }
        Database::getTable('ank_secim')->save($c);
        $this->redirect('backend/ank_secim');
      }
    }

    if( $request->action && $request->action == 'new' ){
      if( $request->isPost() ){
        $c = new Condition();
        foreach ($request->ank_secim as $key => $val){
          $c->add($key, $val);
        }
        Database::getTable('ank_secim')->save($c);
        $this->redirect('backend/ank_secim');
      }
    }     
  }

}


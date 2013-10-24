

  public function {table_name}($request = null)
  {
    $this->fields = Database::getTable('{table_name}')->findColumns();
    $this->fields = array_diff($this->fields, array('id', 'created_at', 'updated_at'));
    
    $this->items = Database::getTable('{table_name}')->findAll(array());
    $this->request = $request;
    
    if( $request->action && $request->action == 'delete' ){
      Database::getTable('{table_name}')->delete($request->id);
      $this->redirectReferer($request);
    }
    
    if( $request->action && $request->action == 'edit' ){
      $this->record = Database::getTable('{table_name}')->find($request->id);
      if( $request->isPost() ){
        $c = new Condition();
        foreach ($request->{table_name} as $key => $val){
          $c->add($key, $val);
        }
        Database::getTable('{table_name}')->save($c);
        $this->redirect('backend/{table_name}');
      }
    }

    if( $request->action && $request->action == 'new' ){
      if( $request->isPost() ){
        $c = new Condition();
        foreach ($request->{table_name} as $key => $val){
          $c->add($key, $val);
        }
        Database::getTable('{table_name}')->save($c);
        $this->redirect('backend/{table_name}');
      }
    }     
  }
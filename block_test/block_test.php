<?php
class block_test extends block_base {
    public function init() {
        $this->title = get_string('test', 'block_test');
    }
    
    public function get_content() {
        global $USER, $DB;
        if ($this->content !== null) {
          return $this->content;
        }
       
        $this->content = new stdClass;
        $this->content->text = '';
        $this->content->footer = '';
        $this->content->text .= get_string('current_user_id', 'block_test');
        $this->content->text .= $USER->id;
        $this->content->text .= '</ br>';
        $this->content->text .= '<ul >';
        $admin_ids = $DB->get_record_sql("select value from {config} where name = 'siteadmins'");
        
        $neighbour_sql = '(select id, firstname, lastname, email from {user} where id < :userid1 and suspended = 0 and deleted = 0 and id not in ('.$admin_ids->value.') limit 1)'
        .' UNION (select id, firstname, lastname, email from {user} where id > :userid2 and suspended = 0 and deleted = 0 and id not in ('.$admin_ids->value.') limit 1)';
        
        $neighbour_users = $DB->get_records_sql($neighbour_sql, array('userid1' => $USER->id,'userid2' => $USER->id));
        foreach ($neighbour_users as $neighbour_user) {
            $this->content->text .= '<li>';
            $this->content->text .= $neighbour_user->id.' '. $neighbour_user->firstname.' '. $neighbour_user->lastname.' '. $neighbour_user->email;
            $this->content->text .= '</li>';
        }
       
        $this->content->text .= '</ul>';
        return $this->content;
      }
}

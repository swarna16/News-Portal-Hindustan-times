<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact_model extends CI_Model
{

    //input values
    public function input_values()
    {
        $data = array(
            'name' => $this->input->post('name', true),
            'email' => $this->input->post('email', true),
            'message' => $this->input->post('message', true)
        );
        return $data;
    }

    //add contact message
    public function add_contact_message()
    {
        $data = $this->input_values();
        //send email
        if ($this->general_settings->mail_contact_status == 1) {
            $this->load->model("email_model");
            $this->email_model->send_email_contact_message($data["name"], $data["email"], $data["message"]);
        }
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert('contacts', $data);
    }

    //get contact messages
    public function get_contact_messages()
    {
		$this->db->order_by('contacts.created_at', 'DESC');
        $query = $this->db->get('contacts');
        return $query->result();
    }

    //get contact message
    public function get_contact_message($id)
    {
        $id = sql_escape_number($id);
        $this->db->where('id', $id);
        $query = $this->db->get('contacts');
        return $query->result();
    }

    //get last contact messages
    public function get_last_contact_messages()
    {
        $this->db->limit(5);
        $query = $this->db->get('contacts');
        return $query->result();
    }

    //delete contact message
    public function delete_contact_message($id)
    {
        $id = sql_escape_number($id);
        $contact = $this->get_contact_message($id);

        if (!empty($contact)) {
            $this->db->where('id', $id);
            return $this->db->delete('contacts');
        }
        return false;
    }

    public function delete_multi_messages($messages_ids)
    {
        if (!empty($messages_ids)) {
            foreach ($messages_ids as $id) {
                $message = $this->get_contact_message($id);
                if (!empty($message)) {
                    $this->db->where('id', $id);
                    $this->db->delete('contacts');
                }
            }
        }
    }

}

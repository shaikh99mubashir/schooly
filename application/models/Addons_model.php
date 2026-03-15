<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Addons_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */

    public function getByProductID($product_id)
    {
        $this->db->select()->from('addons');
        $this->db->where('product_id', $product_id);
        $query = $this->db->get();
        return $query->row();
    }

    public function getAddonWithVersion($product_id)
    {
        $this->db->select()->from('addons');
        $this->db->where('product_id', $product_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->row();
            $result->{'nxt_versions'} = $this->getAddonVersions($result->id, $result->current_version);
            return $result;
        }

        return false;
    }

    public function getAddonVersions($addon_id, $current_version)
    {
        $sql = "SELECT * FROM addon_versions WHERE addon_id = " . $addon_id . "   AND version_order > ( SELECT version_order FROM addon_versions WHERE addon_id =  " . $addon_id . " AND version = '" . $current_version . "' LIMIT 1)";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getAddonByFileName($filename)
    {
        $this->db->select()->from('addons');
        $this->db->where('directory', $filename);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->row();
            return $result;
        }

        return false;
    }

    public function get($id = null)
    {
        $this->db->select()->from('addons');
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row();
        } else {
            return $query->result();
        }
    }

    public function getlimitwithsearch($limit = null, $start = null, $where_condition = array())
    {
        $query        = $this->db->select("addons.*, (SELECT version FROM addon_versions WHERE version_order = (SELECT MAX(version_order) FROM addon_versions WHERE version_order > (SELECT version_order FROM addon_versions WHERE version = addons.current_version AND addon_id = addons.id) AND addon_id = addons.id) AND addon_id = addons.id) as update_version");
        if (!empty($where_condition)) {
            $query->group_start(); // Open bracket
            $query->like('name', $where_condition['search']);
            $query->group_end(); // Close bracket
        }

        $query->from('addons');

        $num_rows = $query->count_all_results('', false);

        if (!is_null($limit) && !is_null($start)) {
            $query->limit($limit, $start);
        }

        $query->order_by("product_order", "asc");
        $query = $query->get();
        return ['count' => $num_rows, 'total_rows' => $query->result()];
    }

    public function countwithsearch($where_condition = array())
    {
        if (!empty($where_condition)) {
            $this->db->like('img_name', $where_condition['search']);
        }
        $this->db->from('addons');
        return $num_rows = $this->db->count_all_results();
    }

    public function addAddonSetupDetails($addon_Array = [])
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        $product = $this->getByProductID($addon_Array['product_id']);

        $update_addon['name'] = $addon_Array["name"];
        $update_addon['description'] = $addon_Array["description"];
        $update_addon['last_update'] = date('Y-m-d H:i:s');

        $this->db->where('id', $product->id);
        $this->db->update('addons', $update_addon);

        $this->db->where('addon_id', $product->id);
        $this->db->delete('addon_versions');

        if (!empty($addon_Array['version_list'])) {

            $add_version = [];

            foreach ($addon_Array['version_list'] as $version_key => $version_value) {
                $inner_Arr = [
                    'addon_id' => $product->id,
                    'version' => $version_value['version'],
                    'version_order' => $version_value['version'],
                    'folder_path' => $version_value['folder_path'],
                    'sort_description' => $version_value['sort_description'],

                ];
                $add_version[] = $inner_Arr;
            }

            $this->db->insert_batch('addon_versions', $add_version);
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            return true;
        }
    }
}

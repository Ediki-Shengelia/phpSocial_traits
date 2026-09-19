<?php


class Db_object
{
    public static $db_name = 'users';
    public static $db_fields = array();
    public function has_attribute($the_attribute)
    {
        return property_exists($this, $the_attribute);
    }
    public static function instantiation($the_record)
    {
        $calling_class = get_called_class();
        $the_obj = new $calling_class;
        foreach ($the_record as $key => $value) {
            if ($the_obj->has_attribute($key)) {
                $the_obj->$key = $value;
            }
        }
        return $the_obj;
    }
    public function properties()
    {
        $the_array = array();
        foreach (static::$db_fields as $db_field) {
            if (property_exists($this, $db_field)) {
                $the_array[$db_field] = $this->$db_field;
            }
        }
        return $the_array;
    }
    public static function find_by_query($sql, $params = [])
    {
        global $database;
        $stmt = $database->prepare($sql, $params);
        $the_array = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $the_array[] = static::instantiation($row);
        }
        return $the_array;
    }
    public static function find_all()
    {
        return static::find_by_query("SELECT * FROM " . static::$db_name);
    }
    public static function find_by_id($id)
    {
        $sql = "SELECT * FROM " . static::$db_name . " WHERE id=:id LIMIT 1";
        $params = [":id" => $id];
        $result = self::find_by_query($sql, $params);
        return !empty($result) ? array_shift($result) : false;
    }
    public function create()
    {
        global $database;
        $propertis = $this->properties();
        unset($propertis['id']);
        $columns = array_map(fn($col) => "`{$col}`", array_keys($propertis));
        $placeholders = array_fill(0, count($propertis), "?");
        $sql = "INSERT INTO " . static::$db_name;
        $sql .= " (" . implode(", ", $columns) . ") ";
        $sql .= " VALUES ( " . implode(",", $placeholders) . ")";
        $database->prepare($sql, array_values($propertis));
        $this->id = $database->the_insert_if();
        return true;
    }
    public function update()
    {
        global $database;
        $propertis = $this->properties();
        unset($propertis['id']);
        $assigments = array();
        foreach (array_keys($propertis) as $column) {
            $assigments[] = "`{$column}=?`";
        }
        $sql = "UPDATE " . static::$db_name;
        $sql .= " SET " . implode(",", $assigments);
        $sql .= " WHERE id = ?";
        $params = array_values($propertis);
        $params[] = $this->id;
        $database->prepare($sql, $params);
        return true;
    }
    public function delete()
    {
        global $database;
        $sql = "DELETE FROM " . static::$db_name . " WHERE id= ? LIMIT 1";
        $stmt = $database->prepare($sql, [$this->id]);
        return $stmt->rowCount() === 1;
    }
    public function save()
    {
        return isset($this->id) ? $this->update() : $this->create();
    }
    public static function count_all()
    {
        global $database;
        $stmt = $database->prepare("SELECT COUNT(*) FROM " . static::$db_name);
        return (int) $stmt->fetchColumn();
    }
}

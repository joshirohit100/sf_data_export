<?php

namespace SfDataExport\data_src;

use SfDataExport\Query;

class Employees extends Query {

  /**
   * {@inheritdoc}
   */
  public function getData() {
    // TODO: We may think of better solution to avoid memory issue.
    // To avoid memory issue while fetching millions of records from database.
    ini_set('memory_limit', '-1');
    $data['term'] = $this->getTermData();
    $data['content'] = $this->getContentData();

    return $data;
  }

  /**
   * {@inheritdoc}
   */
  public function getTermData() {
    $data['titles'] = $this->getTitlesTerm();
    $data['departments'] = $this->getDepartmentsTerm();

    return $data;
  }

  /**
   * Returns unique titles/designations of employees.
   */
  private function getTitlesTerm() {
    $connection = $this->getConnection();
    $sql = 'SELECT DISTINCT title FROM titles';
    $query = $connection->query($sql);
    $query->setFetchMode(\PDO::FETCH_ASSOC);
    $data = $query->fetchAll();

    return $data;
  }

  /**
   * Returns list of departments.
   */
  private function getDepartmentsTerm() {
    $connection = $this->getConnection();
    $sql = 'SELECT dept_no, dept_name FROM departments';
    $query = $connection->query($sql);
    $query->setFetchMode(\PDO::FETCH_ASSOC);
    $data = $query->fetchAll();

    return $data;
  }

  /**
   * {@inheritdoc}
   */
  public function getContentData() {
    $connection = $this->getConnection();

    $data['employees'] = $this->getEmployees($connection);
    $data['dept_emp'] = $this->getDeptEmp($connection);
    $data['dept_mgr'] = $this->getDeptMgr($connection);
    $data['titles'] = $this->getTitles($connection);
    $data['salaries'] = $this->getSalaries($connection);

    return $data;
  }

  /**
   * Returns list of employees.
   */
  private function getEmployees($connection) {
    $sql = 'SELECT * FROM employees';
    $query = $connection->query($sql);
    $query->setFetchMode(\PDO::FETCH_ASSOC);
    $data = $query->fetchAll();

    return $data;
  }

  /**
   * Returns list of employees in departments.
   */
  private function getDeptEmp($connection) {
    $sql = 'SELECT * FROM dept_emp';
    $query = $connection->query($sql);
    $query->setFetchMode(\PDO::FETCH_ASSOC);
    $data = $query->fetchAll();

    return $data;
  }

  /**
   * Returns list of managers in departments.
   */
  private function getDeptMgr($connection) {
    $sql = 'SELECT * FROM dept_manager';
    $query = $connection->query($sql);
    $query->setFetchMode(\PDO::FETCH_ASSOC);
    $data = $query->fetchAll();

    return $data;
  }

  /**
   * Returns salaries of employees.
   */
  private function getTitles($connection) {
    $sql = 'SELECT * FROM titles';
    $query = $connection->query($sql);
    $query->setFetchMode(\PDO::FETCH_ASSOC);
    $data = $query->fetchAll();

    return $data;
  }

  /**
   * Returns salaries of employees.
   */
  private function getSalaries($connection) {
    $sql = 'SELECT * FROM salaries';
    $query = $connection->query($sql);
    $query->setFetchMode(\PDO::FETCH_ASSOC);
    $data = $query->fetchAll();

    return $data;
  }

  /**
   * Database connection.
   *
   * @return \PDO
   *   PDO object.
   */
  public function getConnection() {
    global $config;

    $host = $config['connection']['mysql']['host'];
    $db   = $config['connection']['mysql']['db'];
    $user = $config['connection']['mysql']['user'];
    $pass = $config['connection']['mysql']['password'];

    $options = [\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'];
    $connection = new \PDO("mysql:host=$host;dbname=$db", $user, $pass, $options);

    return $connection;
  }

}
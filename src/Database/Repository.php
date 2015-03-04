<?php
namespace Nogo\Framework\Database;

use Aura\Filter\Filter;
use Aura\Sql\ExtendedPdo;
use Aura\SqlQuery\QueryFactory;
use Nogo\Framework\Database\Relation;
use Nogo\Framework\Database\Scope;

/**
 * Class Repository
 */
interface Repository {

    public function __construct($name, array $columns, ExtendedPdo $connection, QueryFactory $factory, Filter $filter);

    /**
     * @return string table identifier
     */
    public function identifier();

    /**
     * @return string table name
     */
    public function tableName();

    /**
     *
     * @param ExtendedPdo $connection
     */
    public function setConnection(ExtendedPdo $connection);

    /**
     * Add relation to queries.
     *
     * @param \Nogo\Framework\Database\Relation $relation
     */
    public function addRelation(Relation $relation);

    /**
     * Add scope to queries.
     *
     * @param \Nogo\Framework\Database\Scope $scope
     */
    public function addScope(Scope $scope);

    /**
     * Find one entity by id.
     *
     * @param $id
     * @return array | boolean
     */
    public function find($id);

    /**
     * Find one entity by name and value.
     *
     * @param $name
     * @param $value
     * @return array | boolean
     */
    public function findBy($name, $value);

    /**
     * Find with data array, uses identifier.
     *
     * @param array $data
     */
    public function findByData(array $data);

    /**
     * Find all entities.
     *
     * @return array | boolean
     */
    public function findAll();

    /**
     * Insert or update entity.
     *
     * @param array $data
     * @return int last insert id or updated row count
     */
    public function persist(array $data);

    /**
     * Delete entity.
     *
     * @param $id
     * @return int deleted row count
     */
    public function remove($id);

    /**
     * Validate data.
     *
     * @param array $data
     * @return array
     */
    public function validate(array $data);
}
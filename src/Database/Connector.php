<?php
namespace Nogo\Framework\Database;

use Aura\Sql\ExtendedPdo;
use Aura\SqlQuery\QueryFactory;
use Aura\SqlSchema\ColumnFactory;
use Aura\SqlSchema\MysqlSchema;
use Aura\SqlSchema\PgsqlSchema;
use Aura\SqlSchema\SqliteSchema;
use Aura\SqlSchema\SchemaInterface;

class Connector
{
    /**
     * @var string
     */
    protected $adapter = '';
    /**
     * @var string
     */
    protected $dsn = '';
    /**
     * @var string
     */
    protected $username = '';
    /**
     * @var string
     */
    protected $password = '';

    /**
     * @var ColumnFactory;
     */
    protected $columnFactory;

    /**
     * @var QueryFactory
     */
    protected $queryFactory;

    /**
     * Constructor
     *
     * @param $adapter
     * @param $dsn
     * @param $username
     * @param $password
     */
    public function __construct($adapter, $dsn, $username, $password)
    {
        $this->adapter = $adapter;
        $this->dsn = $dsn;
        $this->username = $username;
        $this->password = $password;

        $this->columnFactory = new ColumnFactory();
        if (isset($this->adapter)) {
            $this->queryFactory = new QueryFactory($adapter);
        }
    }

    public function connect()
    {
        return new ExtendedPdo(
            $this->adapter . ':' . $this->dsn,
            $this->username,
            $this->password
        );
    }

    /**
     * @return string
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @param string $adapter
     */
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;
        if (isset($this->adapter)) {
            $this->queryFactory = new QueryFactory($adapter);
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getDsn()
    {
        return $this->dsn;
    }

    /**
     * @param string $dsn
     */
    public function setDsn($dsn)
    {
        $this->dsn = $dsn;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return SchemaInterface
     */
    public function getSchema(\PDO $pdo = null)
    {
        if ($pdo == null) {
            $pdo = $this->connect()->getPdo();
        }

        $result = null;
        switch ($this->adapter)
        {
            case 'mysql':
                $result = new MysqlSchema($pdo, $this->columnFactory);
                break;
            case 'sqlite':
                $result = new SqliteSchema($pdo, $this->columnFactory);
                break;
            case 'postgres':
                $result = new PgsqlSchema($pdo, $this->columnFactory);
                break;
        }
        return $result;
    }

    /**
     *
     * @return QueryFactory
     */
    public function getQueryFactory()
    {
        return $this->queryFactory;
    }
}

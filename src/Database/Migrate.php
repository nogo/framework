<?php
namespace Nogo\Framework\Database;

/**
 * Migrate - Run database migration with pdo.
 *
 * @author Danilo Kühn <dk@nogo-software.de>
 * @package Database
 */
class Migrate
{
    /**
     * Run migration from path. Files must end with '.sql'.
     *
     * @param \PDO $pdo
     * @param type $path Path of files with .sql extension.
     * @return type
     */
    public static function run(\PDO $pdo, $path)
    {
        $result = [];
        $queries = self::extract($path);
        foreach ($queries as $q) {
            $query = trim($q);
            if (!empty($query)) {
                $pdo->query($query . ';');
                $result[] = $query;
            }
        }
        
        return $result;
    }

    /**
     * Retrieve queries from migration files
     * 
     * @param string $path
     * @return array
     * @throws \Exception
     */
    protected static function extract($path)
    {
        $queries = [];
        if (file_exists($path) && is_dir($path)) {
            $files = scandir($path);
            if ($files !== false) {
                foreach ($files as $file) {
                    $fileinfo = pathinfo($path . DIRECTORY_SEPARATOR . $file);
                    if ($fileinfo['extension'] === 'sql') {
                        $sql = file_get_contents($path . DIRECTORY_SEPARATOR . $file);
                        $queries = array_merge($queries, explode(';', $sql));
                    }
                }
            } else {
                throw new \Exception("No migrations found.");
            }
        } else {
            throw new \Exception('Path [' . $path . '] does not exists');
        }


        return $queries;
    }

}

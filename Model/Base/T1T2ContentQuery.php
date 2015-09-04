<?php

namespace CatchThelia1Url\Model\Base;

use \Exception;
use CatchThelia1Url\Model\T1T2Content as ChildT1T2Content;
use CatchThelia1Url\Model\T1T2ContentQuery as ChildT1T2ContentQuery;
use CatchThelia1Url\Model\Map\T1T2ContentTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 't1_t2_content' table.
 *
 *
 *
 * @method     ChildT1T2ContentQuery orderByIdt1($order = Criteria::ASC) Order by the idt1 column
 * @method     ChildT1T2ContentQuery orderByIdt2($order = Criteria::ASC) Order by the idt2 column
 *
 * @method     ChildT1T2ContentQuery groupByIdt1() Group by the idt1 column
 * @method     ChildT1T2ContentQuery groupByIdt2() Group by the idt2 column
 *
 * @method     ChildT1T2ContentQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildT1T2ContentQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildT1T2ContentQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildT1T2Content findOne(ConnectionInterface $con = null) Return the first ChildT1T2Content matching the query
 * @method     ChildT1T2Content findOneOrCreate(ConnectionInterface $con = null) Return the first ChildT1T2Content matching the query, or a new ChildT1T2Content object populated from the query conditions when no match is found
 *
 * @method     ChildT1T2Content findOneByIdt1(int $idt1) Return the first ChildT1T2Content filtered by the idt1 column
 * @method     ChildT1T2Content findOneByIdt2(int $idt2) Return the first ChildT1T2Content filtered by the idt2 column
 *
 * @method     array findByIdt1(int $idt1) Return ChildT1T2Content objects filtered by the idt1 column
 * @method     array findByIdt2(int $idt2) Return ChildT1T2Content objects filtered by the idt2 column
 *
 */
abstract class T1T2ContentQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \CatchThelia1Url\Model\Base\T1T2ContentQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\CatchThelia1Url\\Model\\T1T2Content', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildT1T2ContentQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildT1T2ContentQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \CatchThelia1Url\Model\T1T2ContentQuery) {
            return $criteria;
        }
        $query = new \CatchThelia1Url\Model\T1T2ContentQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildT1T2Content|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        throw new \LogicException('The ChildT1T2Content class has no primary key');
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        throw new \LogicException('The ChildT1T2Content class has no primary key');
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return ChildT1T2ContentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        throw new \LogicException('The ChildT1T2Content class has no primary key');
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildT1T2ContentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        throw new \LogicException('The ChildT1T2Content class has no primary key');
    }

    /**
     * Filter the query on the idt1 column
     *
     * Example usage:
     * <code>
     * $query->filterByIdt1(1234); // WHERE idt1 = 1234
     * $query->filterByIdt1(array(12, 34)); // WHERE idt1 IN (12, 34)
     * $query->filterByIdt1(array('min' => 12)); // WHERE idt1 > 12
     * </code>
     *
     * @param     mixed $idt1 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildT1T2ContentQuery The current query, for fluid interface
     */
    public function filterByIdt1($idt1 = null, $comparison = null)
    {
        if (is_array($idt1)) {
            $useMinMax = false;
            if (isset($idt1['min'])) {
                $this->addUsingAlias(T1T2ContentTableMap::IDT1, $idt1['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($idt1['max'])) {
                $this->addUsingAlias(T1T2ContentTableMap::IDT1, $idt1['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(T1T2ContentTableMap::IDT1, $idt1, $comparison);
    }

    /**
     * Filter the query on the idt2 column
     *
     * Example usage:
     * <code>
     * $query->filterByIdt2(1234); // WHERE idt2 = 1234
     * $query->filterByIdt2(array(12, 34)); // WHERE idt2 IN (12, 34)
     * $query->filterByIdt2(array('min' => 12)); // WHERE idt2 > 12
     * </code>
     *
     * @param     mixed $idt2 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildT1T2ContentQuery The current query, for fluid interface
     */
    public function filterByIdt2($idt2 = null, $comparison = null)
    {
        if (is_array($idt2)) {
            $useMinMax = false;
            if (isset($idt2['min'])) {
                $this->addUsingAlias(T1T2ContentTableMap::IDT2, $idt2['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($idt2['max'])) {
                $this->addUsingAlias(T1T2ContentTableMap::IDT2, $idt2['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(T1T2ContentTableMap::IDT2, $idt2, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildT1T2Content $t1T2Content Object to remove from the list of results
     *
     * @return ChildT1T2ContentQuery The current query, for fluid interface
     */
    public function prune($t1T2Content = null)
    {
        if ($t1T2Content) {
            throw new \LogicException('ChildT1T2Content class has no primary key');

        }

        return $this;
    }

    /**
     * Deletes all rows from the t1_t2_content table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(T1T2ContentTableMap::DATABASE_NAME);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            T1T2ContentTableMap::clearInstancePool();
            T1T2ContentTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildT1T2Content or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildT1T2Content object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public function delete(ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(T1T2ContentTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(T1T2ContentTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        T1T2ContentTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            T1T2ContentTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // T1T2ContentQuery

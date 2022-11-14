<?php


namespace App\Customer\Commons;


use Illuminate\Database\Eloquent\Model;

/**
 *Base repository to handle the data layer aspect of the application, to be extended by all child data repositories
 *
 * @param Model $model
 */
abstract class BaseRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * create a new model data (entity) in the database
     * @param array $item
     * @return array
     */
    public function create(array $item)
    {
        return $this->model->create($item);
    }

    /**
     * find a model item by id with associated relationship if specified
     *
     * @param Integer $item_id ,
     * @param array $relationships
     *
     * @return Model
     */
    public function findById($item_id, array $relationships = [])
    {
        $relations = implode(",", $relationships);
        return count($relationships) > 0 ? (!empty($this->model->find($item_id))? $this->model->find($item_id)->with($relations)->first() : null) : $this->model->find($item_id);
    }

    /**
     *update a model entity by item id
     *
     * @param array $item
     * @param integer $item_id
     * @return integer
     */
    public function update(array $item, $item_id)
    {
        return $this->model->find($item_id)->update($item);
    }

    /**
     * delete a specific model entity by item
     *
     * @param integer $item_id
     *
     * @return boolean
     */
    public function delete($item_id)
    {
        return $this->model->find($item_id)->delete();
    }
}

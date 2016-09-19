<?php namespace App\Transformers;
use Task, Request;
use League\Fractal\TransformerAbstract;

class TaskTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'test'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(Task $task)
    {
        return [
            'id'    				=> (int) $task->id,
            'user_id'               => (int) $task->user_id,
            'task_name'    	        => $task->task_name,
            'assigned_contact_id'   => $task->assigned_contact_id,
            'assigned_name'         => $task->contact->name,
            'due_date'              => $task->due_date,
            'priority'              => $task->priority,
            'categories'            => $task->categories,
            'description'           => $task->description,
            'status'                => $task->status,
            'revision_number'       => $task->revision_number,
        ];
    }

    /**
     * Include Author
     *
     * @return League\Fractal\ItemResource
     */
    public function includeTest(Property $property)
    {
        $test = $property->property;

        return $this->item($test, new PropertyTransformer);
    }

}
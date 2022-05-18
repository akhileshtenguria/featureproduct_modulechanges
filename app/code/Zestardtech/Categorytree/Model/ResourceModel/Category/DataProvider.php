<?php
namespace  Zestardtech\Categorytree\Model\ResourceModel\Category;


use Magento\Framework\Api\Filter;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;

class DataProvider extends AbstractDataProvider
{
    /**
     * @var PoolInterface
     */
    protected $pool;
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param PoolInterface $pool
     * @param array $meta
     * @param array $data
     * @throws LocalizedException
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        PoolInterface $pool,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collectionFactory = $collectionFactory->create();
        $this->pool         = $pool;
        $this->meta         = $this->prepareMeta($this->meta);
        $this->dataPersistor = $dataPersistor;
    }

    /**
     * Prepares Meta
     *
     * @param array $meta
     * @return array
     * @throws LocalizedException
     */
    public function prepareMeta(array $meta)
    {
        $meta = parent::getMeta();

        foreach ($this->pool->getModifiersInstances() as $modifier) {
            $meta = $modifier->modifyMeta($meta);
        }
        return $meta;
    }

    public function addFilter(Filter $filter){
        return null;
    }


    /**
     * @return array
     */
    public function getData()
    {
        if (isset($this->_loadedData)) {
            return $this->_loadedData;
        }
        $items = $this->collectionFactory->getItems();
        foreach ($items as $item) {
            $data = $item->getData();
            if(isset($data['category'])){
            // $data['category'] = explode(',', $data['category']);
            
            }
            $this->_loadedData[$item->getId()] = $data;
            $this->_loadedData=$data;
        }
        $data = $this->dataPersistor->get('name');
        if (!empty($data)) {
            $block = $this->collection->getNewEmptyItem();
            $block->setData($data);
            $this->_loadedData[$block->getId()] = $block->getData();
            $this->dataPersistor->clear('crossell');
        }
        return $this->_loadedData;
    }


    public function getData2()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

   /*     $items = $this->collectionFactory->getItems();
        foreach ($items as $item) {
            $data = $item->getData();
  // print_r($data); 
  // die; 
            $data['category_ids'] = explode(',', $data['entity_id']);
            // $data['select_categories'] = [3,5,6,7];
           //  echo $item->getId();
            $final_data['details'] = $data;
           // die; 
            $this->loadedData[$item->getId()] =  $data;
        }*/
        return $this->loadedData;
    }

}
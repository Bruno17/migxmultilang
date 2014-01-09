/**
 * @author Bruno Perner
 * @copyright 2014
 */

function duplicateTranslations($oldResource, $newResource, $duplicateChildren, $prefixDuplicate, $classname)
{
    global $modx;
    echo $old_id = $oldResource->get('id');
    echo $new_id = $newResource->get('id');

    $c = $modx->newQuery($classname);
    $c->where(array('contentid' => $old_id));

    if ($collection = $modx->getCollection($classname, $c))
    {
        foreach ($collection as $object)
        {
            $newobject = $modx->newObject($classname);
            $newobject->fromArray($object->toArray());
            $newobject->set('contentid', $new_id);
            $newobject->save();
        }
    }

    if ($duplicateChildren)
    {
        $children = $oldResource->getMany('Children');
        if (is_array($children) && count($children) > 0)
        {
            /**
             *  *  *  *  * @var modResource $child */
            foreach ($children as $oldChild)
            {
                $newPagetitle = $prefixDuplicate ? $modx->lexicon('duplicate_of', array('name' => $oldChild->get('pagetitle'))) : $oldChild->get('pagetitle');
                if ($newChild = $modx->getObject('modResource', array('pagetitle' => $newPagetitle)))
                {
                    duplicateTranslations($oldChild, $newChild, $duplicateChildren, $prefixDuplicate, $classname);
                }
            }
        }
    }
}


$packageName = 'migxmultilang';

$packagepath = $modx->getOption('core_path') . 'components/' . $packageName . '/';
$modelpath = $packagepath . 'model/';
if (is_dir($modelpath))
{
    $modx->addPackage($packageName, $modelpath, $prefix);
}

$classname = 'mmlTemplateVarResource';


$event = $modx->Event->name;


if ($event == 'OnEmptyTrash' && $ids = $modx->getOption('ids', $scriptProperties, false))
{

    $c = $modx->newQuery($classname);
    $c->where(array('contentid:IN' => $ids));

    if ($collection = $modx->getCollection($classname, $c))
    {
        foreach ($collection as $object)
        {
            $object->remove();
        }
    }
}

if ($event == 'OnResourceDuplicate')
{

    duplicateTranslations($oldResource, $newResource, $duplicateChildren, $prefixDuplicate, $classname);

}
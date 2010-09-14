<?php
    $taxonomyIds = Set::extract('/Taxonomy/id', $this->data);
    $term_ids=array_intersect($taxonomyIds,  array_keys($taxonomy['2']) );
    $terms=array();
    foreach($term_ids as $term_id)
    {
        $terms[]=$taxonomy['2'][$term_id];
    }
    echo $form->input('TaxonomyTags', array(
    'label' => $vocabularies['2']['title'],
    'type' => 'textarea',
    'value'=> implode(',',$terms)
    ));
?>
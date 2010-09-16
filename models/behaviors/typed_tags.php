<?php
/**
 * Example Behavior
 *
 * PHP version 5
 *
 * @category Behavior
 * @package  Croogo
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class TypedTagsBehavior extends ModelBehavior {
/**
 * Setup
 *
 * @param object $model
 * @param array  $config
 * @return void
 */
    public function setup(&$model, $config = array()) {
        $this->model=&$model;
        if (is_string($config)) {
            $config = array($config);
        }

        $this->settings[$model->alias] = $config;
    }

    public function beforeSave(&$model) {
        $final_tags    = array();

        if( isset($model->data['Node']['TaxonomyTags'] ) ){
            $tags          = explode( ',', $model->data['Node']['TaxonomyTags']);
            $existent_tags = $this->model->Taxonomy->find('all', array('fields'=>array('Taxonomy.id','Term.slug'),'conditions'=>array('Taxonomy.vocabulary_id' => 2)));

            foreach($tags as $tag){
				$tag=trim($tag);
                //create slug
                $slug=preg_replace('/\s/','-', $tag);
                $slug=preg_replace('/[^a-zA-Z0-9\-]/', '', $slug);
                $slug=strtolower($slug);
                $taxonomy_id = null; //try to find current $tag as a term
                foreach( $existent_tags as $existent_tag ){
                    if( $slug == $existent_tag['Term']['slug'] ){
                        $taxonomy_id = $existent_tag['Taxonomy']['id'];
                        break;
                    }
                }

                if( $taxonomy_id == null){ //if tag was not found in existent tags create its term and taxonomy
                    $term=$this->model->Taxonomy->Term->find('first',array('conditions'=>array('Term.slug'=>$slug)));
                    $tag_id=null;
                    if(!isset($term['Term']['id']))
                    {
                        $this->model->Taxonomy->Term->create();
                        $this->model->Taxonomy->Term->set(array(
                            'title' => $tag,
                            'slug'  => $slug
                        ));
                        $this->model->Taxonomy->Term->save();
                        $tag_id = $this->model->Taxonomy->Term->id;
                    }
                    else
                    {
                        $tag_id=$term['Term']['id'];
                    }
                    if($tag_id){
                        $this->model->Taxonomy->create();
                        $this->model->Taxonomy->set(array(
                            'term_id'       => $tag_id,
                            'vocabulary_id' => 2
                        ));
                        $this->model->Taxonomy->save();
                        $taxonomy_id=$this->model->Taxonomy->id;
                    }
                }
                if($taxonomy_id != null && !in_array($taxonomy_id,$this->model->data['Taxonomy']['Taxonomy']) && !in_array($taxonomy_id,$final_tags)){
                    array_push( $final_tags, $taxonomy_id);
                }
            }
            $this->model->data['Taxonomy']['Taxonomy'] = array_merge($this->model->data['Taxonomy']['Taxonomy'], $final_tags);
            return true;
        }
    }

}
?>

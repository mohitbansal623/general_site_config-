<?php

namespace Drupal\general_site_config\Controller;  

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Defines a route controller for displaying node output as json.
 */
class GetPageJson extends ControllerBase {

  /**
   * Handler for request.
   */
  public function getData($key = NULL, $nid = NULL) {
    // retrieve original siteapi key from config.
    $siteapikey = \Drupal::config('siteapikey.config')->get('siteapikey');
    
    // Checking if nid exists in the database.
    $nid_exists = \Drupal::entityQuery('node')->condition('nid', $nid)->execute();
    
    // If siteapi key and nid exists then return json data of node.
    if ($siteapikey == $key && !empty($nid_exists)) {
      $node = \Drupal\node\Entity\Node::load($nid);
      
      // if node type is page.
      if ($node->getType() == 'page') {
        // Converting node Object to array.
        $node_array = (array) $node;
        
        // If want to pass only few fields
        // $node_array = array();
        // $node_array['id'] = $node->id();
        // $node_array['title'] = $node->getTitle();
        
        // returning json response.
        return new JsonResponse($node_array);
      }
      else {
        // displaying access denied message if node type is not page .
       throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
      }
   }
   else {
       // displaying access denied message if siteapikey or nid does not exists.
       throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
   }
 }
} 


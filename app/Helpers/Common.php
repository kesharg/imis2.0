<?php
namespace App\Helpers;
  /*
class Common {
  
    public static function nullIfEmpty($variable) {
        return $variable ? $variable : null;
    }

    /*
     
      SWM COMMON FUNCTIONS
     
     /

    public static function getCollectionPointTypes(){
        return [
          "Normal" => "Normal",
          "Focused" => "Focused"
        ];
    }

    public static function getCollectionPointServiceType(){
        return [
            "Private" => "Private",
            "Mahalaxmi Central Ward" => "Mahalaxmi Central Ward"
        ];
    }

    public static function getCollectionPointStatus(){
        return [
            "Open Ground" => "Open Ground",
            "Container" => "Container"
        ];
    }

    public static function getWasteTypes(){
        return [
            "Organic" => "Organic",
            "Inorganic" => "Inorganic",
            "Mixed" => "Mixed"
        ];
    }

    public static function getLandfillSiteStatus(){
        return [
            "In Operation" => "In Operation",
            "Closed" => "Closed",
            "Proposed" => "Proposed"
        ];
    }

    public static function getLandfillSiteOperators(){
        return [
            "Municipality" => "Municipality",
            "Private" => "Private",
            "PPPP" => "PPPP",
            "Other" => "Other"
        ];
    }
*/


    /* 
    helper to compare a phrase with an array of keywords, and returns true if it matches any of the keywords
    */

    class KeywordMatcher
    {
        public static function matchKeywords($phrase, $keywords)
        {
            foreach ($keywords as $keyword) {
                if (stripos($phrase, $keyword) !== false) {
                    return true;
                }
            }
            
            return false;
        }
    }

    

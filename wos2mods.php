<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        //
        // CONFIGURABLE VARIABLES
        // 
        // Handle declares where you want the MODS Records to be saved to.
        // Make sure you have write permissions for the destination
        // Pro-tip: have output go to a Folder, not your desktop!
        // Example: $handle = "/output/{$key->IID}.xml";
        $handle = "/output/{$key->IID}.xml";
        //
        // filePath declares where the JSON .txt file is located. 
        // The easiest configuration is to place the .txt file in the same
        // directory as this wos2mods.php file and then just put the
        // filename between the quotes.
        // Example: $filePath = "WoS_Endnote_12-14-16_OASelections-xlsx.txt";
        $filePath = "WoS_Endnote_12-14-16_OASelections-xlsx.txt";
        ////////////////
        //
        // SCRIPT START
        //
        //
        $jsonFile = file_get_contents("{$filePath}") or die("Could not open JSON File");
        $jsonDecoded = json_decode($jsonFile);
        
        foreach($jsonDecoded->rows as $key){
           
            // Start XML Build
            $xml = new SimpleXMLElement('<mods xmlns="http://www.loc.gov/mods/v3" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:mods="http://www.loc.gov/mods/v3" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:etd="http://www.ndltd.org/standards/metadata/etdms/1.0/" xmlns:flvc="info:flvc/manifest/v1" xsi:schemaLocation="http://www.loc.gov/standards/mods/v3 http://www.loc.gov/standards/mods/v3/mods-3-4.xsd" version="3.4"></mods>');
            
            // Build Title
            
            $xml->addChild('titleInfo');
            $xml->titleInfo->addAttribute('lang','eng');
            if($key->{'Nonsort An'}){
                $xml->titleInfo->addChild('nonsort',"An");
            }
            if($key->{'Nonsort A'}){
                $xml->titleInfo->addChild('nonsort',"A");
            }
            if($key->{'Nonsort The'}){
                $xml->titleInfo->addChild('nonsort',"The");
            }
            $xml->titleInfo->addChild('title', htmlspecialchars($key->{'Base Title'}));

            if($key->Subtitle){
                $xml->titleInfo->addChild('subtitle', htmlspecialchars($key->Subtitle));
            }
            
            // Build Author
            
            $authorsExploded = explode("; ", $key->Author);
            
            foreach($authorsExploded as $authorKey){
                $brokenDown = explode(", ", $authorKey);
                
                $a = $xml->addChild('name');
                $a->addAttribute('type', 'personal');
                $a->addAttribute('authority','local');

                $a->addChild('namePart',htmlspecialchars($brokenDown[1]))->addAttribute('type','given');
                $a->addChild('namePart',htmlspecialchars($brokenDown[0]))->addAttribute('type','family');

                $a->addChild('role');
                $r1 = $a->role->addChild('roleTerm', 'author'); 
                $r1->addAttribute('authority', 'rda');
                $r1->addAttribute('type', 'text');
                $r2 = $a->role->addChild('roleTerm', 'aut'); 
                $r2->addAttribute('authority', 'marcrelator');
                $r2->addAttribute('type', 'code');         
            }
            
            
            // Build originInfo
      
            $xml->addChild('originInfo');
            $xml->originInfo->addChild('dateIssued', htmlspecialchars($key->Date));
            $xml->originInfo->dateIssued->addAttribute('encoding','w3cdtf');
            $xml->originInfo->dateIssued->addAttribute('keyDate','yes');
            
            
            // Build abstract, if field is not empty
            
            if($key->{'Abstract Note'}){ 
                $xml->addChild('abstract', htmlspecialchars($key->{'Abstract Note'}));
            }
            
            // Build identifiers
      
                // IID
                $xml->addChild('identifier',$key->IID)->addAttribute('type','IID');
      
                // DOI
                if($key->DOI){
                    $xml->addChild('identifier',$key->DOI)->addAttribute('type','DOI');
                }
            
            // Build Related Item
            
            $xml->addChild('relatedItem')->addAttribute('type','host');
            $xml->relatedItem->addChild('titleInfo');
            $xml->relatedItem->titleInfo->addChild('title', htmlspecialchars($key->{'Publication Title'}));
            
            if($key->ISSN){
                $xml->relatedItem->addChild('identifier',$key->ISSN)->addAttribute('type','issn');
            }
            
            if($key->Volume || $key->Issue || $key->Pages){
                $xml->relatedItem->addChild('part');
                
                if($key->Volume) {
                    $volXML = $xml->relatedItem->part->addChild('detail');
                    $volXML->addAttribute('type','volume');
                    $volXML->addChild('number', htmlspecialchars($key->Volume));
                    $volXML->addChild('caption','vol.');
                }
                
                if($key->Issue) {
                    $issXML = $xml->relatedItem->part->addChild('detail');
                    $issXML->addAttribute('type','issue');
                    $issXML->addChild('number', htmlspecialchars($key->Issue));
                    $issXML->addChild('caption','iss.');
                }
                
                if($key->Pages) {
                    $pagXML = $xml->relatedItem->part->addChild('extent');
                    $pagXML->addAttribute('unit','page');
                    $xml->relatedItem->part->extent->addChild('start', htmlspecialchars($key->Start));
                    
                    // In this JSON set, not all entries have ends. Data is prepared with Start and End columns based on Open Refine alterations
                    if($key->End){
                        $xml->relatedItem->part->extent->addChild('end', htmlspecialchars($key->End));
                    }
                }
            }
            
            // Build Notes
            
            if($key->{'Manual Tags'}){
                $xml->addChild('note', htmlspecialchars($key->{'Manual Tags'}))->addAttribute('displayLabel','Keywords');
            }
            
            if($key->{'Publication note'}){
                $xml->addChild('note', htmlspecialchars($key->{'Publication note'}))->addAttribute('displayLabel','Publication Note');
            }
            
            // Build FLVC extensions
        
            $flvc = $xml->addChild('extension')->addChild('flvc:flvc', '', 'info:flvc/manifest/v1');
            $flvc->addChild('flvc:owningInstitution', 'FSU');
            $flvc->addChild('flvc:submittingInstitution', 'FSU');

            // Add other static elements
            
            $xml->addChild('typeOfResource', 'text');
            $xml->addChild('genre', 'text')->addAttribute('authority', 'rdacontent');
            $xml->addChild('language');
            $l1 = $xml->language->addChild('languageTerm', 'English');
            $l1->addAttribute('type', 'text');
            $l2 = $xml->language->addChild('languageTerm', 'eng');
            $l2->addAttribute('type', 'code');
            $l2->addAttribute('authority', 'iso639-2b');
            $xml->addChild('physicalDescription');
            $rda_media = $xml->physicalDescription->addChild('form', 'computer');
            $rda_media->addAttribute('authority', 'rdamedia'); 
            $rda_media->addAttribute('type', 'RDA media terms');
            $rda_carrier = $xml->physicalDescription->addChild('form', 'online resource');
            $rda_carrier->addAttribute('authority', 'rdacarrier'); 
            $rda_carrier->addAttribute('type', 'RDA carrier terms');
            $xml->physicalDescription->addChild('extent', '1 online resource');
            $xml->physicalDescription->addChild('digitalOrigin', 'born digital');
            $xml->physicalDescription->addChild('internetMediaType', 'application/pdf');
            $xml->addChild('recordInfo');
            $xml->recordInfo->addChild('recordCreationDate', date('Y-m-d'))->addAttribute('encoding', 'w3cdtf');
            $xml->recordInfo->addChild('descriptionStandard', 'rda');
                
            
        //
        // WRITE MODS FILE
        //
        
        $output = fopen($handle,"w");

        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());
        fwrite($output,$dom->saveXML());
        fclose($output);
            
        print "Processed {$key->IID}! <a href=\"file://{$handle}\">View XML</a>";
        print "<br>";    
        
        }  // forEach closing bracket
?>
    </body>
</html>

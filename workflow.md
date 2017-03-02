General Concept and Background Information

- The goal is to capture as much FSU-affiliated publications appearing in Web of Science with minimal participation necessary on the part of authors. Part of the workflow focuses on decreasing the time-intensive tasks of identifying scholarship and transferring metadata between different platforms. Using a combination of Web of Science, Zotero, and SHERPA/RoMEO API calls in Google Sheets, thousands of publications can be identified and efficiently checked for embargo requirements, manuscript v. publisher copy deposit permissions, and whether the publisher targets institutions with OA policies/mandates. 
- The end result of the workflow process leaves you with a set of publications that you can easily filter to discover different sub-sets of articles: (1) those that can be deposited into an institutional repository as publisher versions; (2) those that can be deposited into an institutional repository as accepted manuscripts/final drafts; and (3) those that only allow pre-print versions to be deposited into institutional repositories. Interspersed with this information are checks for embargo restrictions and a direct link to the SHERPA/RoMEO page for the journal for quick and easy reference. 
    - For institutions with open access policies in conjunction with an institutional repository, articles in sub-set (1) published after the effective date of the policy are immediately actionable for ingest. 
    - Articles in sub-sets (2) and (3) are prime candidates for a targeted outreach effort asking authors to submit article manuscripts to the repository. This can be accomplished in different ways, one of which includes using mail-merge to efficiently contact authors to invite them to reply back with an attached copy of a final draft or pre-print version of their scholarship. 

- Any of the articles identified in the workflow outlined above can then be passed through the wos2mods.php script to transform the spreadsheet metadata into a valid MODS record. 
  

Helpful Assets and What You Need to Get Started

- Short video demoing the process of using SHERPA/RoMEO API calls in Google Sheets:[https://youtu.be/ZMyKVHM5nOc](https://youtu.be/ZMyKVHM5nOc) 
    - Pastebin of original source code for script: [http://pastebin.com/sXknBHDq](http://pastebin.com/sXknBHDq) 
    - I created additional functions and updated other functions that are helpful for our workflow with institutional repositories, which are [available at my GitHub](https://github.com/aretteen/wos2mods/blob/master/GoogleSheetScript). 

- Access to [Web of Science](http://webofknowledge.com) 
- A [Google Drive account](https://www.google.com/drive/) to create and store Google Sheets (and use the SHERPA/RoMEO API calls) 
- [Zotero Standalone](https://www.zotero.org/download/) (free application that requires signing up for a free account) 
- [OpenRefine](http://openrefine.org/) 
- My [GitHub wos2mods Repository](https://github.com/aretteen/wos2mods) for wos2mods.php script and documentation 
  
  
  
  

Get Affiliation Result Sets & Export Using Web of Science

1. Using Basic Search, select “Organization-Enhanced” from the drop down. 
    1. Once selected, click on “Select from Index” and find “Florida State University.” Note that “Florida State University System” is misleading; that selection is better understood as “State of Florida University System” and will return articles from any Florida University. 

2. Alter the search criteria for timespan or other criteria as desired 
3. Click “Search” 
4. At the top of the first page of results, there is a “Save to” drop down. Select “Save to Endnote Desktop” 
    1. For “Number of Records” insert the entire record range, not “All records on page”. Only 500 results can be saved at a time. For example, if 2,259 results were returned you would put in the range “1 to 500,” save the records, and come back to the results and save “501 to 1000,” etc. 
    2. For “Record Content” select “Full Record” 
    3. Click “Send” 
    4. You should be automatically prompted to save the file “savedrecs.ciw” to your computer. You can save the file with any name, or leave it as “savedrecs,” but definitely do not change the “.ciw” extension. 

  

Import into Zotero Collection

1. Open Zotero 
2. File -&gt; Import 
3. Browse and select the exported “.bib” file and click “Open” 
4. The contents of the exported “.bib” file should appear in a collection with the same name as the filename that was imported. 
5. It may be helpful to rename this collection with contextual information, like the database the content was retrieved from and the date. 
    1. Create a sub-collection named “WoS-Date” and every time you import a set of records, drag those records into the new sub-collection. At the end of the process, you will have just 1 subcollection that you can work with in the next steps. 
    2. Note - From the imported folder, select all records and drag to the WoS subcollection. Then, right-click and delete the import folder. Do not delete items in the subcollection, since that will still delete the records as they exist in the WoS subcollection. Just delete the collection. 

  

Export from Zotero to CSV File Format

1. Right-click on the collection containing the imported information and select “Export Collection” 
2. For Format, select “CSV” 
3. Leave “Export Notes” unchecked 
4. Click OK 
5. Name and save the file on your computer. 
  

Import CSV File into Google Sheets

1. Log in to Google Drive and create a new spreadsheet. 
2. Name the new Sheet appropriately and select a location for it. 
3. Click File -&gt; Import 
4. Upload the CSV file from your computer. 
5. Select the following import options: 
    1. For “Import action” select “Replace spreadsheet” 
    2. For “Separator character” select “Detect automatically” 
    3. For “Convert text to numbers and dates” select “No” 

6. Add Script for SHERPA/RoMEO API calls to Google Sheet 

1. Copy [this script](https://github.com/aretteen/wos2mods/blob/master/GoogleSheetScript). 
2. In the Google Sheet, go to Tools -&gt; Script Editor. In the Code.gs document, delete the starter function and replace it with the copied script. 

1. Save the script editor. You can close it, too. 
2. Note: The script is now associated with that sheet. Every new sheet you make you have to add the script language again. I found it useful to create a blank “template sheet” with only the script loaded into it, which can be copied (File -&gt; Make a Copy) and used for different Web of Science searches. 
  

Set Up Google Sheet & Run Script

1. On the Google Sheet, you now need to add new columns.  
    1. Right click on the first column (Letter A, it should be “Key”) and select “Insert 1 left” to create a new column. 
    2. Repeat this until you have 6 blank columns 
    3. Name each column one of the following: checkOAmandate, embargo, finaldraft, pubpdf, getlink, getcolor 
        1. Doing this helps you to remember what the actual function name is when calling it in the Google Sheet. 

2. Run script on 1 row 
    1. To run the script, you have to call each function in the respective column and associate that function with the ISSN column. You can do this by typing in the Column/Cell code for that ISSN, or by clicking on the cell when calling the function.  
        1. [This video](https://youtu.be/ZMyKVHM5nOc) (from 0:45 to 1:55) helps visualize this process if this written instruction is not clear. 
        2. For each column, you need to type in “=”, then the function name, then the cell location in parenthesis. 
            1. Example for pubpdf function with ISSN in cell M1: =pubpdf(M1) 

        3. When you hit enter, the script will run. 
        4. Do this for each function on the first row. 

3. Drag rows down and Google Sheets will automatically run the functions for the other records. 
4. Replace results with text to avoid triggering script upon reloading Sheet. 
    1. Whenever the script is done running on a large set of fields, you can simply select those fields, copy, and then immediately paste special -&gt; values only. This will replace the function equation with plain text and when you re-load the Google Sheet, the script won’t run again for those cells. This is an important step, do not skip. 

  

Tips and Recommendations

- When handling 500+ records on a sheet, run the script in sets of 500 to avoid issues of timing out. 
- Run this script on a computer that you can keep the sheet open for as long as it takes to run the script. It takes some time for the script to run API checks for each field. You can lower this time by choosing only to run the functions you are most interested in. pubpdf is probably the most helpful, followed by embargo, then finaldraft, then getcolor, then checkOAmandate. My “getlink” function doesn’t actually use the Sherpa API and doesn’t add significant time to the script processing time. 
    - For a contextual example, it took my Macbook Air under 90 minutes to process approximately 2,000 records. 

- Beware of excessive use imposed by Google. Currently you are limited to 20,000-50,000 URL lookups per day. See https://docs.google.com/macros/dashboard for UrlFetch specifically. 
- To avoid excessive usage, after running a set of ISSNs, copy and paste over the discovered values by going to Edit -&gt; Paste special -&gt; Paste values only 
  
  
  

Creating MODS Records from Result Set Using OpenRefine + PHP Script

- First, I want to attribute the initial concept and instructions for a similar workflow to Sara Allain and the University of Toronto Scarborough: [https://www.utsc.utoronto.ca/digitalscholarship/content/blogs/converting-spreadsheets-modsxml-using-open-refine](https://www.utsc.utoronto.ca/digitalscholarship/content/blogs/converting-spreadsheets-modsxml-using-open-refine) 
- Conceptual Overview: We are going to be taking the Result Set stored in the Google Sheet, process the data within it in Open Refine and prepare the data for processing by the PHP script, export the data from Open Refine as a JSON file, then process that JSON file with a PHP script that will generate MODS records for each item  
  

1. Run an affiliation search, create Google Sheet, and run the Sherpa script as described above. 
2. Re-order the Google Sheet by “pubpdf” - this will group together all of the results with “Publisher's version/PDF may be used” and “Publisher's version/PDF may be used after an embargo period”. 
3. Create a new Google Sheet. Title it something like “WoS-Date-OA_Selections” to indicate what it is. 
4. Copy the header row in the initial Google sheet and paste it in the new sheet. 
5. Select all of the records that allow for Publisher PDF deposit, copy them, and paste them into the new Sheet. 
6. Delete columns that are completely blank, or not necessary to include in the MODS record (there are a lot since Zotero has so many metadata fields) 
    1. Like: URL, Date Modified, Access Date, NumPages, Number of Volume, Journal Abbreviation, Short Title, Series, Series Number, Series Text, Series Title, Publisher, Place, Language, Rights, Type, Archive, Archive Location, Library Catalog, Call Number, Notes, File Attachments, Link Attachments, Automatic Tags, Editor, Series Editor, Translator, Contributor, Attorney Agent, Book Author, Cast Member, Commenter, Composer, Cosponsor, Counsel, Interviewer, Producer, Recipient, Reviewed Author, Scriptwriter, Words By, Guest, Number, Edition, Running Time, Scale, Medium, Artwork Size, Filing Date, Application Number, Assignee, Issuing Authority, Country, Meeting Name, Conference Name, Court, References, Reporter, Legal Status, Priority Numbers, Programming Language, Version, System, Code, Code Number, Section, Session, Committee, History, Legislative Body 
    2. The following are the crucial fields for a proper MODS record: Authors, Title, Abstract (optional), Publication Title, Volume, Issue, Publication Date, Page Range, Keywords (optional), Publication Note (optional), Preferred Citation (optional), Grant Number (optional), Identifier (DOI, optional), Extra (contains IID info) 

7. Export the Google Sheet to an Excel file (File -&gt; Download As -&gt; Microsoft Excel) 
8. Download and install [OpenRefine](http://openrefine.org/download.html) if you do not already have it. 
9. Open OpenRefine by going to [http://127.0.0.1:3333/](http://127.0.0.1:3333/) in your browser. 
10. Create a project by importing the Excel spreadsheet from your computer. 
    1. Note: the Google Data link service does not appear to work anymore, so the excel import is the quickest option 
    2. Click Create Project 

11. Data Manipulation & MODS Record Preparation 
    1. Note that text in red is case-sensitive and must be entered in correctly for the PHP script to work. 
    2. IID creation 
        1. Go to the Extra column (which should contain the Web of Science identifier). Click the arrow and select Edit column -&gt; Add column based on this column 
        2. New column name: IID 
        3. Expression (quotes included): "FSU_libsubv1_wosgrabber_" + substring(value, 6) 

    3. Publication note creation 
        1. Go to the DOI column, click the arrow, and select Edit column -&gt; Add column based on this column 
        2. New column name: Publication note 
        3. Expression (quotes included): “The publisher’s version of record is available at https://doi.org/” + value 
        4. Click OK. Records without DOIs will not have a publication note generated. 

    4. Page range 
        1. Go to the Pages column, click the arrow -&gt; Edit Column -&gt; Split into several columns 
        2. For “How to Split Column” select “by separator” and make the separator character a “-” instead of a comma. (put the dash without quotes) 
        3. You can de-select Remove this column if you want to preserve the original cells. Removing the column is ok, too. 
        4. You’ll now have two extra columns called Pages 1 and Pages 2. Rename “Pages 1” to “Start” and “Pages 2” to “End” by clicking the arrow -&gt; Edit Column-&gt; Rename column. 
        5. Note: some of the initial values only have the start page and not the end page, which is ok. Only the “start” value will be included in the MODS record if this is the case 

    5. Title manipulation 
        1. For the title field, we have to manipulate it in different ways. We are trying to piece out the “nonsort” words (a, an, the), the base title, and any subtitle. 
        2. Titlecase (to fix the all upper case titles) 
            1. Click on the arrow for Title -&gt; Edit cells -&gt; Common transforms -&gt; To titlecase 

        3. Subtitle 
            1. Click on the arrow for Title -&gt; Edit column -&gt; Split into several columns 
            2. For “How to Split Column” select “by separator” and make the separator character a “:” (colon) instead of a comma. (put the colon in without quotes) 
            3. Split into “2” columns at most 
            4. De-select “Remove this column” so you can retain the original title string 
            5. Rename “Title 1” to “Base Title” 
            6. Rename “Title 2” to “Subtitle” 

        4. Nonsort 
            1. Using Open Refine, we have to take the title and check for nonsort words (“A”, “An”, “The”). At end of process, you will have three new columns with true/false values. 
            2. Nonsort A 
                1. Go to arrow for Title -&gt; Edit column -&gt; Add column based on this column… 
                2. Enter new column name: Nonsort A 
                3. Enter the following expression: startsWith(value, "A ") 
                    1. Note there is an intentional trailing space after A 

                4. Click Ok 

            3. Nonsort An 
                1. Go to arrow for Title -&gt; Edit column -&gt; Add column based on this column… 
                2. Enter new column name: Nonsort An 
                3. Enter the following expression: startsWith(value, "An ") 
                4. Click Ok 

            4. Nonsort The 
                1. Go to arrow for Title -&gt; Edit column -&gt; Add column based on this column… 
                2. Enter new column name: Nonsort The 
                3. Enter the following expression: startsWith(value, "The ") 
                4. Click Ok 

        5. Manual Tags 
            1. These tags can serve as a Keyword string in the MODS record, but they have to be changed to be separated by comma instead of semicolon 
            2. Click the arrow for Manual Tags -&gt; Edit cells -&gt; Transform… 
            3. Paste the following expression: replace(value, ";", ",") 
            4. Click Ok. 

12. Export Result Set in JSON 
    1. In the top right corner of Open Refine, click “Export” -&gt; Templating… 
    2. Keep everything as default and click “Export” 
    3. A .txt file will be downloaded to your computer. 

13. Configure and Run MODS Generation Script 
    1. Open up the wos2mods.php file in a code editor and configure the two variables at the top, $handle (path to output folder) and $filePath (path to the JSON .txt file) 
    2. Run wos2mods.php from a local webserver, or run the command-line utility. 
    3. The script should begin generating MODS files to the output folder. 

14. Ingest into your repository!

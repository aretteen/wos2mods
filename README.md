# wos2mods
## Purpose
This utility is a key component in the automated workflow for FSU's institutional repository, DigiNole.

After exporting citations from Web of Science and processing the metadata using a combination of Zotero, Google Sheets, and Open Refine,
users can take the JSON .txt export from OpenRefine and process the metadata into individual MODS-compliant XML files. This utility thus
drastically reduces the amount of time it would take to process the citation records and does not require the participation of scholars to
increase the amount of content on the institutional repository.

## How to Use
I developed two ways to run the utility. 

You can use the base package and run it through a local webserver and browser. Prior to running the script, simply open the wos2mods.php file and edit the configuration variables for the output destination and the file path to the JSON .txt file.

You can use the executable package in wos2mods_bash to run the script through the command line. The output folder is hardcoded as /wos2mods_bash/output. Simply run the wos2mods executable with one argument, the file path to the JSON txt file.
Example: /path/to/wos2mods /path/to/jsonfile.txt

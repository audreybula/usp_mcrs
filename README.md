# Moodle Course Request Plugin #

Moodle Course Request Plugin is a Moodle block plugin that automates the processes of course shell creation and backing up and restoring course shells. This plugin is intended for the Center of Flexible Learning (CFL) team at the University of the South Pacific.

## Overview ##

The Moodle Course Request Plugin is being developed to reduce the workload for the CFL staff and also reduce the time taken to process a request. The current system consists of manual processes which can easily be automated, and automation is what the plugin will achieve. Academic staff will use the plugin to request for a course shell. The request is validated by the system and upon successful validation, the course shell will be automatically created and the Lecturer and Course Designer are automatically enrolled. The plugin will have auto generated emails which will be sent at each step of the process for status tracking purposes. The final activity in the course request process is an auto generated email to the Lecturer and Course Designer that the course shell has been created and is now ready for use. The Course Designer provides moodle support to staff and students. The CFL Admin will use the plugin to see the status of requests, assign/re-assign support staff to courses, and monitor the number of courses a support staff is supporting. In the event that the system cannot automatically create a course shell, the CFL Admin will request the Academic to correct any errors or uncertainties associated with a request. Once corrected, the system can carry on with the automation. This plugin greatly improves the current system by reducing the time taken to process a request. It automates the processes of request validation, course creation, and support staff assignment. It also reduces the need for human input because the Course Request form is a “smart” form. Once the academic fills a field in the form, the other fields get filled automatically. The MCRS plugin will also improve the University’s operations in that lecturers can get their courses ready for the new semester well before time, students will not panic because courses are not showing on moodle, the University’s helpdesk will get less emails regarding moodle courses not showing, and, the CFL team will be more efficient and productive in handling course requests.

## Installation ##

Moodle Course Request Plugin should be installed like any other block. See the [Moodle Docs page on plugin installation](https://docs.moodle.org/37/en/Installing_plugins#Installing_a_plugin) for more info.
  
## Features ##

* Automatic Course Shell Creation
* Automatic Backup And Restore Of Course Shells
* Automatic Notifications Via Email
* Automatic Enrollment Of Course Lecturer And Course Designer
* Automatic Balancing Of Support Staff Assignments

### Academic ###
* Make A Request
* Edit A Request
* View Own Requests

### CFL Admin ###
* Approve Requests
* Re-Assign Support Staff
* Modify Email Templates
* Request For More Information
* View All Requests
* View Support Staff Assignments

### Moodle Admin ###
* Modify Copyfrom and Copyto Fields
* Modify Email Templates

## Contributions ##

Contributions of any form are welcome. GitHub pull requests are preferred. Report any bugs or requests through our GitHub [issue tracker](https://github.com/audreybula/usp_mcrs/issues).

## License ##

Moodle Course Request Plugin adopts the same license that Moodle does.

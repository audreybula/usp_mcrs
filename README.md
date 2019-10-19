# Moodle Course Request #


Admin View Page.
=======
USP Moodle Course Request plugin that will be part of the University’s moodle platform. This plugin will be open source and is licensed under the GNU general public license. The Moodle Course Request plugin is intended for the CFL Staff and USP Academic staff. Its purpose is to reduce the workload of course creation for the CFL staff and provide an interface through which the CFL Admin can monitor staff assignments.

Although Moodle is accessible to many users in the University, the plugin will only be made available to users that have the roles Admin, Teacher, and Course Creator. The Academics (Teacher) will be able to request for course shells and the CFL staff (Admin) will handle the requests which cannot be processed by the system. The CFL staff will also be able to see all staff assignments.

The plugin will be a block plugin which means that it will appear as a block on the moodle dashboard when it is made visible. It will be built according to standards followed by moodle so that it will work well within moodle.

## Installation ##

Install the plugin using the `usp_mcrs.zip` template, then navigate to the moodle site administration -> plugins -> install plugin then drag and drop the zip file onto the provided area and select install. 

After successfully installing the plugin:
  - delete `usp_mcrs` folder located in `moodle/blocks/`
  - clone repository into `moodle/blocks/` directory 
  - open `usp_mcrs` folder into your desired IDE/TextEditor and you are good to go.



## License ##

2019 IS314 Group 4 <you@example.com>

This program is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program.  If not, see <http://www.gnu.org/licenses/>.

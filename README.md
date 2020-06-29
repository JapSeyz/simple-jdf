# JDF #

A package for creating simple JDF messages

[JDF](https://en.wikipedia.org/wiki/Job_Definition_Format) is an XML standard used to send files to digital printers.

## Installation ##

`composer require japseyz/simple-jdf`

## Usage ##

**Create a new JDF file**

```
// instantiate a Job
$job = new \JapSeyz\SimpleJDF\Job();
// add a new print file to the Job
$job->setPrintFile('http://absolute/path/to/file.pdf');
// save the raw JDF to a file
file_put_contents('filename.jdf', $job->asXML());
```

### Credits ###

Thanks to Joe Pritchard for his JoePritchard/jdf package which this is based upon.

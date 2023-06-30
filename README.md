# redmine_monitor

The redmine monitor is a proxied view into our production redmine system allowing for alternative means of displaying 
and interacting with the redmine system.  This is needed since the version of our redmine system predates CORS 
requirements we can not generate an only frontend system that utilizes the redmine api directly, even with the 
api keys.

## Running the solution
There is no public production image to use as part of this solution.  The container must be built locally before it 
is run.

### Build Production Container

From the solution's base directory

```shell
docker build --no-cache -f devops/images/redmine_php_prod.Dockerfile -t redmine_monitor:latest .
```

### Set up your `docker-compose.override.yml` file

From the solution's `devops/redmine_prod` directory
```shell
cp docker-compose.override.yml.example docker-compose.override.yml
```
Update the `REDMINE_` environment variables in the `docker-compose.override.yml` file and save the file.

### Start the solution in docker

From the solution's `devops/redmine_prod` directory
```shell
docker-compose up -d
```

---
<dl>
    <dt>
        <em>Based of the <a href="https://github.com/ryanwhowe/symfony-template">symfony-template</a> GitHub Template project</em>
    </dt>
    <dd>
        <strong>by <a href="https://github.com/ryanwhowe" target="_blank">Ryan Howe</a></strong>
    </dd>
</dl>
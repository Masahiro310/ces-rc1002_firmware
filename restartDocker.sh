docker container stop test_ces-rc1002
docker container rm test_ces-rc1002
docker rmi cesrc1002:0.9
docker build -t cesrc1002:0.9 .
docker container run -d -p 8083:80 --name test_ces-rc1002 cesrc1002:0.9
# docker cp ./test_retry.php test_ces-rc1002:/opt/piLab/www/

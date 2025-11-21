pada code :
ini untuk di local pake /uploads/
target.image = imgObj.path.startsWith('http') ? imgObj.path : '/uploads/' + imgObj.path;

kalau di hosting
target.image = imgObj.path.startsWith('http') ? imgObj.path : '' + imgObj.path;

karna path folder uploadnya beda

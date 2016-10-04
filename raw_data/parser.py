in_file = 'publication.txt'
out_file = 'new.txt'

def clean(string):
    if not string:
        return string
    items = string.split('\t')
    paper = items[2]
    papers = paper.split(' ')
    ispname = 0
    isjname = 0
    pname = []
    jname = []
    for item in papers:
        if ispname == 1:
            if item.endswith('"') or item.endswith("``") or item.endswith('``,'):
                pname.append(item.strip(',"`'))
                ispname = 0
                isjname = 1
                continue
            else:
                pname.append(item)
        if item.startswith('"') or item.startswith('``'):
            ispname = 1
            pname.append(item.strip('"`,'))
        if isjname == 1:
            jname.append(item.strip(','))
            if (item.endswith(',')):
                break

    pstring = ' '.join(pname)
    jstring = ' '.join(jname)
    return items[0] + '\t' + items[1] + '\t' + pstring + '\t' + jstring
    

#with open(in_file) as i, open(out_file, 'w') as o:
i = open(in_file)
o = open(out_file, 'w')
o.write('\n'.join([clean(line) for line in i]))

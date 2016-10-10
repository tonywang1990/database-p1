#!/usr/bin/env python
#-*-coding=utf-8-*-
"""
This code is for crawling the online for professors in Texas A&M College of Engineering
"""
import requests
import codecs
import json
import copy
import bs4
import re
import os
import sys  
import sets

reload(sys)  
sys.setdefaultencoding('utf8')

"""
Example Block first obtained by BeautifulSoup:
          <div class='record medium-6 columns'>
			<hr />
              <img class="rightalign" src="/media/448655/dougherty.jpg" alt="Dougherty, Edward" title="Dougherty, Edward" /> # image link
            <h4>
                <a href="/electrical/people/edougherty" title="Edward R. Dougherty">Edward R. Dougherty</a> # secondary link for more personal info and name
            </h4>
                <div class="add-bottom">
                    <h5>Robert M. Kennedy ’26 Chair Professor </h5> # title of the faculty
                    <h5>Distinguished Professor</h5>
                </div>
              <p>
                  <span>Office: 214G WEB</span><br />
               
                  <span>Phone: 979.862.8154</span><br />
            
                  <span>Email: <a href="mailto:e-dougherty@tamu.edu">e-dougherty@tamu.edu</a></span>
              </p>
          </div>
"""

SAVE_PATH = 'C:\Users\dell\Dropbox\CSCE608\project1' 

DEP_HASH={ # hash table for department link to department name:
    'http://engineering.tamu.edu/electrical/people/faculty': "Electrical and Computer Engineering",
    'http://engineering.tamu.edu/cse/people/faculty': 'Computer Science and Engineering', 
    'http://engineering.tamu.edu/materials/people/faculty': 'Material Science and Engineering',
    'http://engineering.tamu.edu/aerospace/people/faculty': 'Aerospace Engineering',
    'http://engineering.tamu.edu/biomedical/people/faculty': 'Biomedical Engineering',
    'http://engineering.tamu.edu/chemical/people/faculty': 'Chemial Engineering',
    'http://engineering.tamu.edu/civil/people/faculty': 'Civil Engineering',
    'http://engineering.tamu.edu/etid/people/faculty': 'Engineering Technology and Industrial Distribution',
    'http://engineering.tamu.edu/industrial/people/faculty': 'Industrial and Systems Engineering',
    'http://engineering.tamu.edu/nuclear/people/faculty': 'Nuclear Engineering',
    'http://engineering.tamu.edu/ocean/people/faculty':'Ocean Engineering',
    'http://engineering.tamu.edu/petroleum/people/faculty': 'Petroleum Engineering'
}

def extract_photo(s, start_url):
    img = re.search('"[\w\/\-_]*\.(jpg|png|jepg|)"', s)
                
    if img:
        photo = img.group(0)
        photo = photo.replace("\"","")
        photo = '/'.join(start_url.split('/')[:-3]) + photo
    else:
         photo = ""
    return photo

def extract_second_link(s,start_url):
    sec_link = re.search('"\/[\w\/\-\+\s()]+"', s)

    if sec_link:
        link = sec_link.group(0)
        link = link.replace("\"","")
        link = '/'.join(start_url.split('/')[:-3]) + link
    else:
        link = ""
    #if not link:
        #print "The secondary link is empty!! See the detailed information below"
        #print s
    return link

"""
def extract_name(str):
    whole = re.search('<a href="/(.+?)<\/a>', s)
    name = ""
    if whole:
        whole_part = whole.group(0)
        part = re.search('">[\w\.\s\-\,]+<', whole_part)
        if part:
            name = part.group(0)        
            name = re.sub('>|<|"', '', name)
    if not name:
        print s
    return name
"""

def extract_name(block):
    name_block = block.find_all('h4')
    if not name_block:
        return ""
    name = name_block[0].get_text()
    name = re.sub('[\r\n\t]','',name)
    return name

def extract_title(block):
    title_block = block.find_all('h5')
    title = ""
    if not title_block:
        return title
    for title_str in title_block:
        if re.search('Professor|Lecturer', title_str.get_text()):
            title = title_str.get_text()
            title = re.sub('[\r\n\t]','',title)
            break
    return title
"""
def extract_title(str):
    whole = re.search('<h5>(.+?)<\/h5>', s) # find the things inside <h5>...</h5>
    title = ""
    for w in whole:
        if re.search('Professor| Lecturer', w): # only find the matching one:
            title = w
            title = re.sub('<h5>|<\/h5>','', title)
        
    return title
"""

def extract_office(s):
    whole = re.search('Office: (.+?)<', s)
    office = ""
    if whole:
        office = whole.group(0)
        office = re.sub('<|Office: ','',office)

    return office

def extract_phone(s):
    whole = re.search('Phone: (.+?)<', s)
    phone = ""
    if whole:
        phone = whole.group(0)
        phone = re.sub('<|Phone: ','',phone)

    return phone

def extract_email(s):
    whole = re.search('">(.+?)<\/a>', s)
    email = ""
    if whole:
        email = whole.group(0)
        email = re.sub('>|<\/a>|"','', email)

    return email


class Spider():
    def __init__(self):
        self.start_urls =['http://engineering.tamu.edu/electrical/people/faculty','http://engineering.tamu.edu/cse/people/faculty', 'http://engineering.tamu.edu/materials/people/faculty', 'http://engineering.tamu.edu/aerospace/people/faculty','http://engineering.tamu.edu/biomedical/people/faculty','http://engineering.tamu.edu/chemical/people/faculty','http://engineering.tamu.edu/civil/people/faculty','http://engineering.tamu.edu/etid/people/faculty','http://engineering.tamu.edu/industrial/people/faculty','http://engineering.tamu.edu/nuclear/people/faculty','http://engineering.tamu.edu/ocean/people/faculty','http://engineering.tamu.edu/petroleum/people/faculty']
        headers = {'User-Agent:Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.85 Safari/537.36'}
        self.session = requests.Session()
        self.session.headers = headers   
        self.pid = 0  # publication id
        self.rid = 0 # research area id
        self.bid = 0 # basic information id
        self.eid = 0 # research information id
    
    def get_request(self,url):
        """send the get request:"""
        #print url
        response = self.session.get(url,timeout=120)
        if response.status_code == 200:
            return response.content
        else:
            return None

    def dump_data(self, result_str, dirname, filename):
        save_dir = os.path.join(SAVE_PATH, dirname)
        if not os.path.exists(SAVE_PATH):
            os.mkdir(SAVE_PATH)
        if not os.path.exists(save_dir):
            os.mkdir(save_dir)
        file_path = os.path.join(save_dir,filename)
        save_file = codecs.open(file_path,"a", encoding='utf-8')
        
        save_file.write(result_str)
        save_file.close()

    def delete_files(self, dirname, filenames):
        dir = os.path.join(SAVE_PATH, dirname)
        if not os.path.exists(SAVE_PATH):
            os.mkdir(SAVE_PATH)
        if not os.path.exists(dir):
            os.mkdir(dir)
        
        for filename in filenames:
            file_path = os.path.join(dir,filename)
            if os.path.exists(file_path):
                os.remove(file_path)
        
    def extract_research_information(self, name, block, soup, second_link):
        lines = block.find_all("div", attrs={'class': "card"})
        email = ""
        resume = ""
        scholar = ""
        website = ""
    
        for link in lines[0].find_all('a'):
            text = link.get_text()
            if re.search('@', text):
                email = text
            else:
                email = "\N"
            if re.search('Curriculum Vitae|Resume | CV', text):
                resume = link.get('href')
                if not re.search('^http:\/\/', resume):
                    resume = "http://engineering.tamu" + resume # only for the college of engineering
            else:
                resume = "\N"
            if re.search('Google Scholar Profile', text):
                scholar = link.get('href')
            else:
                scholar = "\N"    
            if re.search('Website', text):
                website = link.get('href')

        if not website:
            website = second_link
            
        result_str = str(self.eid) + "\t" + name + "\t" + website + "\t" + resume + "\t" + scholar + "\t" + email + "\t"
        self.eid = self.eid + 1
        """ find the education information: """
        research_blocks = soup.find_all("div", attrs={'class':"medium-8 columns"})
        education = ""
        
        
        if not research_blocks:
            print "No research information block is found for faculty named: %s! " %name
            result_str = result_str + "\N" + "\n"
            self.dump_data(result_str, "data", "academic.txt")
            return
        
        uls = research_blocks[0].find_all('ul', attrs ={'class':"nobullets"})

        if not uls:
            print "No research content is found for faculty named: %s!" %name
            result_str = result_str + "\N" + "\n"
            self.dump_data(result_str, "data", "academic.txt")
            return
        for ul in uls:
            if ul.previous.previous_sibling.name == 'h3' and re.search("Education", ul.previous.previous_sibling.text):
                lists = ul.find_all('li')
                if not lists:
                    result_str = result_str + "\N" + "\n"
                    self.dump_data(result_str, "data", "academic.txt")
                    return 
                education = lists[0].get_text()
                break
        result_str = result_str + education + "\n"
        self.dump_data(result_str, "data", "academic.txt")
        

        """
        ind = 0
        for h3 in research_blocks[0].find_all('h3'):
            text = h3.get_text()
            if not re.search('Education', text):
                ind = ind+1
                continue
            if ind >= len(uls):
                continue
            education_block = uls[ind]
            if not education_block:
                continue;
            education_list = education_block.find_all('li')
            if not education_list:
                continue;
            education = education_list[0].get_text()
        
        result_str = result_str + education + "\n"
        self.dump_data(result_str, "data", "research.txt")
        """
       
    def extract_research_areas(self, name, soup):
        research_blocks = soup.find_all("div", attrs={'class':"medium-8 columns"})
        
        if not research_blocks:
            self.dump_data(str(self.rid) + "\t" +name + "\t" + "\N" + "\n", "data", "research.txt")
            self.rid = self.rid + 1
            return

        
        firstH3 = research_blocks[0].find('h3')
        if not firstH3 or not re.search("Research Interests", firstH3.get_text()):
            self.dump_data(str(self.rid) + "\t" +name + "\t" + "\N" + "\n", "data", "research.txt")
            self.rid = self.rid + 1
            return
        # get the ones in the paragragh <p>:
        for nextSibling in firstH3.next_siblings:
            if nextSibling.name == 'p' and not re.search("include", nextSibling.get_text()):
                area = nextSibling.get_text()
                area = re.sub('[\r\n\t]','',area)
                if area == ' ' or len(area) < 5:# invalid information do not need
                    continue
                self.dump_data(str(self.rid) + "\t" +name + "\t" + area + "\n", "data", "research.txt")
                self.rid = self.rid + 1
            if nextSibling.name == 'h3':
                break
        # according to my observation, if the research area exists, will be followed by <p> or <ul>!
        uls = research_blocks[0].find_all('ul')
        if not uls:
            return
        mySet = sets.Set([])
        for ul in uls:  # there are some interesting cases: https://engineering.tamu.edu/petroleum/people/tblasingame
            if  ul.has_attr('class'):
                break
            for li in ul.find_all('li'):    
                
                area = li.get_text()
                if re.search(":", area):
                    continue
                area = re.sub('[\r\n\t]','',area)
                
                if area not in mySet: # for the nested ul, which will cause some duplicates
                    self.dump_data(str(self.rid) + "\t" +name + "\t" + area + "\n", "data", "research.txt")
                    self.rid = self.rid+1 
                    mySet.add(area)
            
            
    """
    def extract_research_areas(self, name, soup):
        research_blocks = soup.find_all("div", attrs={'class':"medium-8 columns"})
        
        if not research_blocks:
            self.dump_data(str(self.rid) + "\t" +name + "\t" + "" + "\n", "data", "area.txt")
            self.rid = self.rid + 1
            return

        uls = research_blocks[0].find_all('ul')
        
        if not uls:
            self.dump_data(str(self.rid) + "\t" +name + "\t" + "" + "\n", "data", "area.txt")
            self.rid = self.rid + 1
            return
    
        ind = 0
        for h3 in research_blocks[0].find_all('h3'):
            text = h3.get_text()
            if not re.search('Research Interests', text):
                ind = ind+1
                continue
            
            area_block = uls[ind]
            if not area_block:
                #print "No research area found for faculty: %s !" %name
                self.dump_data(str(self.rid) + "\t" +name + "\t" + "" + "\n", "data", "area.txt")
                self.rid = self.rid + 1
                return

            area_list = area_block.find_all('li')
            if not area_list:
                self.dump_data(str(self.rid) + "\t" +name + "\t" + "" + "\n", "data", "area.txt")
                self.rid = self.rid + 1
                return

            for area_tag in area_list:
                 area = area_tag.get_text()
                 re.sub('[\r\n\t]','',area)
                 self.dump_data(str(self.rid) + "\t" +name + "\t" + area + "\n", "data", "area.txt")
                 self.rid = self.rid+1
    """             

    def extract_research_publication(self, name, soup):
        research_blocks = soup.find_all("div", attrs={'class':"medium-8 columns"})
        if not research_blocks:
            self.dump_data(str(self.pid) + "\t" +name + "\t" + "\N" + "\n", "data", "publication.txt")
            self.pid = self.pid+1
            return
        # TODO: find the selected publication first! like the area!
        ps = research_blocks[0].find_all('p')
        if not ps:  # selected publications are encoded in <p>!
            #print "No publication list found for faculty named %s !" %name
            self.dump_data(str(self.pid) + "\t" +name + "\t" + "\N" + "\n", "data", "publication.txt")
            self.pid = self.pid+1
            return
        # new method:
        firstH3 = research_blocks[0].find('h3')
        mySet = sets.Set([])
        for nextSibling in firstH3.next_siblings:
            if nextSibling.name == 'h3' and re.search("Publications", nextSibling.get_text()):
                for nnSibling in nextSibling.next_siblings:
                    if nnSibling.name == 'p' and not re.search("View", nnSibling.get_text()):
                        publication = nnSibling.get_text()
                        publication = re.sub('[\r\n\t]','',publication)
                        if publication == ' ' or len(publication) < 5:# invalid information do not need
                            continue
                        if publication not in mySet: # avoid some duplicate publication for some professor!
                            self.dump_data(str(self.pid) + "\t" +name + "\t" + publication +"\n", "data", "publication.txt")
                            self.pid = self.pid + 1
                            mySet.add(publication)
                    if nnSibling.name == 'div':
                        break
        """
        for p_tag in ps:
            publication  = p_tag.get_text()
            #publication = unicode(publication.strip(codecs.BOM_UTF8), 'utf-8')
            if re.match('View', publication):
                continue
            publication = re.sub('[\r\n\t]','',publication)
            if publication == ' ' or len(publication) < 5:# invalid information do not need
                continue
            self.dump_data(str(self.pid) + "\t" +name + "\t" + publication +"\n", "data", "publication.txt")
            self.pid = self.pid + 1
        """    
        

    def parse_second_page(self, name, second_link):
        #second_link = "http://engineering.tamu.edu/electrical/people/mkezunovic"
        html = self.get_request(second_link)

        if not html:
            print "fail to open the link: %s" %second_link
            return
        soup = bs4.BeautifulSoup(html,'lxml')
        blocks = soup.find_all("div", attrs={'class': "medium-4 columns"})
        for block in blocks:
            if not block.find_all("div", attrs={'class': "card"}):
                continue
            # extract the email, resume, scholar page and personal website and education:
            self.extract_research_information(name, block, soup, second_link)
            # extract research areas:
            self.extract_research_areas(name, soup)
            # extract publication information:
            self.extract_research_publication(name, soup)

    def crawl(self):
        """start here:"""
        filename_lists = ["basic.txt", "research.txt", "publication.txt", "academic.txt"]
        self.delete_files("data", filename_lists)
        for start_url in self.start_urls:
            
            html = self.get_request(start_url)            
            
            if not html:
                print "failed to get html source from start url: %s"%start_url
                continue
    
            soup = bs4.BeautifulSoup(html, 'lxml')
           
            blocks = soup.find_all("div", attrs={'class': "record medium-6 columns"})
            
            # begin for each faculty
            print "current at link:%s" %start_url
            
            url_head = '/'.join(start_url.split('/')[:-1])
            department = DEP_HASH[start_url]
            print department
            cnt = 0
            for block in blocks:  
                my_str = block.encode('utf-8')
                # find the link to the img :
                photo = extract_photo(my_str, start_url)
                my_str = re.sub('<img(.+?)\/>','',my_str)

                # find the secondary link and name:
                second_link = extract_second_link(my_str, start_url)
                
                name = extract_name(block)
                my_str = re.sub('<a href="/(.+?)<\/a>','',my_str)
                

                # find the title:
                title = extract_title(block)
                
                my_str = re.sub('<h5>(.+?)</h5>','',my_str)
                
                # find the office, phone and Email:
                office = extract_office(my_str)
                phone = extract_phone(my_str)
                email = extract_email(my_str)
                cnt = cnt+1
                mySet = sets.Set([])
                if not second_link:
                    continue
                if name not in mySet:
                    mySet.add(name)
                else:
                    continue
                #if not (second_link and title and office and phone and email and name):  
                    #print block
                #dump the personal basic information:   
                dump_str = str(self.bid) + "\t" + name + "\t"  + department + "\t" + title + "\t" + office + "\t" + phone + "\t" + email + "\t" + photo + "\n"
                self.dump_data(dump_str, "data", "basic.txt")
                self.bid = self.bid + 1
                # start visiting secondary page 
                self.parse_second_page(name, second_link)

        print self.pid
#print(soup.div.h4.a['title'])
#print(soup.div.h4.a['href'])
if __name__ == '__main__':
    spider = Spider()
    spider.crawl()
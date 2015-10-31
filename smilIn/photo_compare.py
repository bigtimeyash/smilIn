#Source: http://opencv-python-tutroals.readthedocs.org/en/latest/py_tutorials/py_feature2d/py_feature_homography/py_feature_homography.html
import os
os.system("ln -s opencv/lib/libopencv_contrib.2.4.dylib /usr/local/Cellar/opencv/2.4.12/lib/libopencv_contrib.2.4.dylib")
os.system("ls -l /usr/local/Cellar/opencv/2.4.12/lib/libopencv_contrib.2.4.dylib")
import sys
sys.path.append("numpy/1.9.2_1/lib/python2.7/site-packages/")
import numpy as np
print sys.path
print "AAAAAAAAAAAAAAA"
sys.path.append("opencv/lib/python2.7/site-packages/")
print sys.path
import cv2
from matplotlib import pyplot as plt

MIN_MATCH_COUNT = 10
print sys.argv[1]
image1 = sys.argv[1].split(",")[0]
image2 = sys.argv[1].split(",")[1]
img1 = cv2.imread(image1,0)          # queryImage
img2 = cv2.imread(image2,0) # trainImage

# Initiate SIFT detector
sift = cv2.SIFT()

# find the keypoints and descriptors with SIFT
kp1, des1 = sift.detectAndCompute(img1,None)
kp2, des2 = sift.detectAndCompute(img2,None)

FLANN_INDEX_KDTREE = 0
index_params = dict(algorithm = FLANN_INDEX_KDTREE, trees = 5)
search_params = dict(checks = 50)

flann = cv2.FlannBasedMatcher(index_params, search_params)

matches = flann.knnMatch(des1,des2,k=2)

# store all the good matches as per Lowe's ratio test.
good = []
for m,n in matches:
    if m.distance < 0.7*n.distance:
        good.append(m)

print "Num Matches: " + str(len(good))

if len(good)>MIN_MATCH_COUNT:
	print "true " + str(len(good))
	src_pts = np.float32([ kp1[m.queryIdx].pt for m in good ]).reshape(-1,1,2)
	dst_pts = np.float32([ kp2[m.trainIdx].pt for m in good ]).reshape(-1,1,2)

	M, mask = cv2.findHomography(src_pts, dst_pts, cv2.RANSAC,5.0)
	matchesMask = mask.ravel().tolist()

	h,w = img1.shape
	pts = np.float32([ [0,0],[0,h-1],[w-1,h-1],[w-1,0] ]).reshape(-1,1,2)
	dst = cv2.perspectiveTransform(pts,M)

	img2 = cv2.polylines(img2,[np.int32(dst)],True,255,3)

else:
	print "false"
	print "Not enough matches are found - %d/%d" % (len(good),MIN_MATCH_COUNT)
	matchesMask = None
draw_params = dict(matchColor = (0,255,0), # draw matches in green color
                   singlePointColor = None,
                   matchesMask = matchesMask, # draw only inliers
                   flags = 2)

#plt.imshow(img1, 'gray'),plt.show()
#plt.imshow(img2, 'gray'),plt.show()
#img3 = cv2.drawMatches(img1,kp1,img2,kp2,good,None,**draw_params)

#plt.imshow(img3, 'gray'),plt.show()
